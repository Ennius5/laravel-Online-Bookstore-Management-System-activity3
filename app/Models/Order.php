<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status'
    ];

    // Define order status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Helper method to check if order is cancellable
    public function isCancellable()
    {
        return $this->status === self::STATUS_PENDING;
    }

    // Accessor for subtotal
    public function getSubtotalAttribute()
    {
        return $this->orderItems->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });
    }
}
