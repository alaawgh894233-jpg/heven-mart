<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $fillable = ['status_ar', 'status_en'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'status_id');
    }
}
