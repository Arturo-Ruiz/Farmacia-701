<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'delivery_type',
        'payment_method',
        'products',
        'product_request',
        'day_rate_value',
        'total_amount',
    ];

    protected $casts = [
        'products' => 'array',
        'day_rate_value' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
