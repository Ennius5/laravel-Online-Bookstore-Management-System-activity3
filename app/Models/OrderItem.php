<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use \OwenIt\Auditing\Auditable;

class OrderItem extends Model implements AuditableContract
{
    //
    use HasFactory, Auditable;

    protected $fillable = [
        'order_id',
        'book_id',
        'quantity',
        'unit_price'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function book(){
        return $this->belongsTo(Book::class);
    }

    // Subtotlal accessor
    //
    public function getSubtotalAttribute(){
        $subtotal = $this->quantity * $this->unit_price;
        return number_format($subtotal, 2);
    }
}

