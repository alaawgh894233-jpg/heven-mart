<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name_ar', 'name_en', 'image', 'is_active'];

    public function scopeFilter($query, ?string $search = null)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'LIKE', "%$search%")
                    ->orWhere('name_en', 'LIKE', "%$search%");
            });
        }
        return $query;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
