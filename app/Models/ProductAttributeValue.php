<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductAttributeValue extends Pivot
{
    protected $table = 'product_attribute_values';
    protected $fillable = ['product_id',
        'attribute_value_id',
        'quantity'
        ,'price_impact'
    ];
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute_value')
            ->withPivot(['value_en', 'value_ar', 'price_impact', 'quantity'])
            ->using(ProductAttributeValue::class)
            ->withTimestamps();
    }

}
