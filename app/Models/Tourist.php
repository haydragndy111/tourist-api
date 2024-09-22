<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Tourist extends Model
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

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

}
