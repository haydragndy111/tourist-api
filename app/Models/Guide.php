<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Guide extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'f_name',
        'l_name',
        'address',
        'mobile',
        'description',
    ];

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

}
