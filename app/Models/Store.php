<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Store extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_ar', 'name_en', 'description_ar', 'description_en', 'logo',
        'status', 'rating', 'num_of_rate','is_featured','user_id'
    ];
    protected $hidden = [];

 //   protected static function booted()
//    {
//        static::addGlobalScope('featured', function ($builder) {
//            $builder->where('status_approve', 'approved')->orderBy('is_featured', 'desc');
//        });
//    }

// Store.php


    public function scopeAdminFiltered($query, $filters)
    {
        return $query
            ->when($filters['status'] ?? false, fn($q) =>
            $q->where('status', $filters['status']))
            ->when($filters['search'] ?? false, fn($q) =>
            $q->where(function ($subQuery) use ($filters) {
                $subQuery->where('name_ar', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('name_en', 'like', '%' . $filters['search'] . '%');
            })
            )
            ->when($filters['sort_by'] ?? false, fn($q) =>
            $q->orderBy($filters['sort_by'], $filters['sort_direction'] ?? 'asc'));
    }

    public function scopeUserFiltered($query, $filters)
    {
        return $query
            ->where('status', 'approved')
            ->when($filters['search'] ?? false, fn($q) =>
        $q->where("name_ar", 'like', '%' . $filters['search'] . '%')->orWhere("name_en", 'like', '%' . $filters['search'] . '%'))
            ->when($filters['sort_by'] ?? false, fn($q) =>
            $q->orderBy($filters['sort_by'], $filters['sort_direction'] ?? 'asc'));
    }

//public function scopeFilterUser($query, array $filters)
//{
//
//    if (isset($filters['search'])) {
//        $query->where(function ($query) use ($filters) {
//            $query->where('name_ar', 'like', '%' . $filters['search'] . '%')
//                ->orWhere('name_en', 'like', '%' . $filters['search'] . '%');
//        })->with('user.profile', 'address');
//    }
//    $query->where('status_approve','approved')
//        ->orderBy('is_featured', 'desc');
//}

public function scopeFilterAdmin($query, array $filters)
{
    if (isset($filters['search'])) {
        $query->where(function ($query) use ($filters) {
            $query->where('name_ar', 'like', '%' . $filters['search'] . '%')
                ->orWhere('name_en', 'like', '%' . $filters['search'] . '%');
        });
    }
    if (isset($filters['status_approve'])) {
        $query->where('status_approve',$filters['status_approve'])
            ->orderBy('created_at', 'desc');
    }
}

//    public function getUserEmailAttribute()
//    {
//        return $this->user()->first()->email;
//    }

//    public function getUserNameAttribute()
//    {
//        return $this->user()->first()->profile()->first()->first_name.' '.$this->user()->first()->profile()->first()->last_name;
//    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'store_id', 'id');
    }
    public function isFeatured()
    {
        return $this->is_featured ;
    }

    public function statusApprove()
    {
        return $this->status_approve ;
    }

    public function getUrlLogo()
    {
        if($this->attributes['logo']){
            return Storage::url($this->attributes['logo']);
        }
        else
            return Storage::url('default/logo.jpg');
    }

}
