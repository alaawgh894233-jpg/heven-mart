<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'total_price', 'quantity'];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function updateTotalPrice()
    {
        $total = $this->items()->with('product')->get()
            ->sum(fn($item) => $item->quantity * $item->product->price);

        $this->update(['total_price' => $total]);
    }

}
