<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, softDeletes;

    public $fillable = [
        'name_ar', 'name_en', 'parent_id', 'is_active', 'image'
    ];

    public function scopeFilter($query, $filters)
    {
        return $query
            ->when($filters['search'] ?? false, fn($q) =>
            $q->where(function ($subQuery) use ($filters) {
                $subQuery->where('name_ar', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('name_en', 'like', '%' . $filters['search'] . '%');
            })
            );
    }


    public function getFullPathAttribute(string $lang)
    {
        $names = [];
        $category = $this;

        while ($category) {
            array_unshift($names, $category->{'name_'.$lang});
            $category = $category->parent;
        }

        return implode(' > ', $names);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }






    public function isLeaf():bool
    {
        return $this->children()->count() ===  0 ;
    }


    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function isTopCategory()
    {
        return is_null($this->parent_id) ;
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }






}
