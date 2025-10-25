<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Profile extends Model
{

    use HasApiTokens,  HasFactory;
    protected $fillable = [
        'user_id',
        'first_name',
        'birth_day',
        'last_name',
        'gender',
        'profile_picture',
    ];
    public function users(){
        return $this->belongsTo(User::class);
    }
}
