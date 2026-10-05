<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewCouponNotification extends Notification
{
    use Queueable;

    public $coupon;

    public function __construct($coupon)
    {
        $this->coupon = $coupon;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Novo Cupom Disponível!',
            'message' => 'Use o cupom ' . $this->coupon->code . ' para ganhar R$ ' . number_format($this->coupon->amount, 2, ',', '.') . ' de saldo!',
            'coupon_id' => $this->coupon->id
        ];
    }
}
