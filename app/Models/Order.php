<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'store_id', 'address_id', 'code', 'payment_method',
        'date', 'total_price', 'count_items', 'delivery_charge',
        'discount_coupon', 'price'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function store() {
        return $this->belongsTo(Store::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }

}
