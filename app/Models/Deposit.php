<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = [
        'user_id',
        'payment_id',
        'amount',
        'status',
        'qr_code',
        'qr_code_base64'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
