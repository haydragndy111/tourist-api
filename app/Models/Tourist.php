<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Tourist extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'tour_id',
        'f_name',
        'l_name',
        'email',
        'password',
        'description',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function tours()
    {
        return $this->belongsToMany(Tour::class, 'tourist_tour');
    }

}
