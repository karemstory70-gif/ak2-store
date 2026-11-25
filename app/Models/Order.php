<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'subtotal',
        'discount_amount',
        'shipping_amount',
        'tax_amount',
        'total',
        'coupon_code',
        'currency',
        'status',
        'payment_method',
        'payment_status',
        'transaction_id',
        'customer_notes',
        'admin_notes',
        'placed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'placed_at' => 'datetime',
        ];
    }

    /**
     * The customer who placed this order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Products included in this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}