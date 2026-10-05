<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'api_order_id',
        'service_id',
        'service_name',
        'link',
        'quantity',
        'charge',
        'status',
        'comments',
        'answer_number',
        'username',
        'runs',
        'interval',
        'api_response',
        'refund_status',
        'refund_reason',
        'refunded_at',
        'refunded_by'
    ];

    protected $casts = [
        'refunded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function refundedBy()
    {
        return $this->belongsTo(User::class, 'refunded_by');
    }

    /**
     * A refunded order never gets refunded again.
     */
    public function scopeRefunded($query)
    {
        return $query->where('refund_status', 'approved');
    }

    public function isRefunded(): bool
    {
        return $this->refund_status === 'approved';
    }

    /**
     * Sincroniza o status real dos pedidos com a API do fornecedor (SMMHub) em lote.
     */
    public static function syncApiStatuses($orders)
    {
        if (empty($orders)) {
            return;
        }

        // Filtra pedidos com api_order_id que ainda não estão finalizados
        $activeOrders = collect($orders)->filter(function ($order) {
            $currentStatus = strtolower($order->getRawOriginal('status') ?? $order->status);
            return !empty($order->api_order_id) && !in_array($currentStatus, ['completed', 'concluído', 'canceled', 'cancelado']);
        });

        if ($activeOrders->isEmpty()) {
            return;
        }

        $ids = $activeOrders->pluck('api_order_id')->implode(',');
        $apiKey = Setting::get('smm_api_key', 'dbe48289d6d94754380128791a3824a4628489962707031a450f9ed11ec226e6');
        $apiUrl = 'https://smmhub.com.br/api/v2';

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)->asForm()->post($apiUrl, [
                'key' => $apiKey,
                'action' => 'status',
                'orders' => $ids
            ]);

            $data = $response->json();
            if (is_array($data)) {
                foreach ($activeOrders as $order) {
                    $orderData = $data[$order->api_order_id] ?? null;
                    if ($orderData && isset($orderData['status'])) {
                        $rawStatus = $orderData['status'];
                        $newStatus = ucfirst($rawStatus);
                        if ($order->getRawOriginal('status') !== $newStatus) {
                            $order->status = $newStatus;
                            $order->saveQuietly();
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Se houver timeout ou instabilidade externa, não interrompe a renderização
        }
    }

    /**
     * Se passar de 24h da criação e estiver pendente/processando, marca automaticamente como Completed.
     */
    public function getStatusAttribute($value)
    {
        $pendingStatuses = ['pending', 'pendente', 'processing', 'in progress', 'in_progress'];
        if ($this->created_at && $this->created_at->addHours(24)->isPast() && in_array(strtolower($value), $pendingStatuses)) {
            if ($this->exists && $value !== 'Completed') {
                $this->attributes['status'] = 'Completed';
                // Salva silenciosamente no banco para persistir o status de entregue
                $this->saveQuietly();
            }
            return 'Completed';
        }
        return $value;
    }
}
