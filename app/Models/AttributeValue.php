<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'value_en',
        'value_ar',
        'status',
        'suggested_by'
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
    public function products() {
        return $this->belongsToMany(Product::class,'product_attribute_value','attribute_value_id','product_id')
            ->withPivot('price_impact','quantity')->withPivot('attribute_id')
            ->withTimestamps();
    }

    public function scopeApproved($q) {
        return $q->where('status','approved');
    }
    public function suggestedBy()
    {
        return $this->belongsTo(User::class, 'suggested_by');
    }
    public function images()
    {
        return $this->hasMany(ImageProduct::class);
    }


}
