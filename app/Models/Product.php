<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_ar', 'name_en', 'price', 'description_ar', 'description_en',
        'discount', 'store_id', 'category_id', 'status', 'stock',
        'unit_en', 'unit_ar', 'rate', 'num_of_purchase', 'is_featured','version'
    ];

    protected $dates = ['deleted_at'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_values')
            ->withPivot('quantity', 'price_impact') // تأكد أن العمود موجود
            ->withTimestamps();
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute_values')
            ->withPivot('attribute_value_id', 'price_impact')
            ->withTimestamps();
    }

    public function primaryImage()
    {
        return $this->hasOne(ImageProduct::class)->where('is_primary', true);
    }

    public function images()
    {
        return $this->hasMany(ImageProduct::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    //Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePriceBetween($query, $min = null, $max = null)
    {
        if ($min !== null) $query->where('price', '>=', $min);
        if ($max !== null) $query->where('price', '<=', $max);
        return $query;
    }

    public function scopeRateBetween($query, $min = null, $max = null)
    {
        if ($min !== null) $query->where('rate', '>=', $min);
        if ($max !== null) $query->where('rate', '<=', $max);
        return $query;
    }

    //حساب السعر مع السمات المحددة
    public function getPriceWithAttributes(array $valueIds = [])
    {
        $base = $this->price;
        if (!empty($valueIds)) {
            $extra = $this->attributeValues()
                ->whereIn('attribute_values.id', $valueIds)
                ->sum('product_attribute_values.price_impact'); // من pivot table
            return $base + $extra;
        }
        return $base;
    }

}
