<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class Tour extends Model
{
    use HasFactory;

    const STATUS_CLOSED = 0;
    const STATUS_OPENED = 1;

    protected $fillable = [
        'guide_id',
        'driver_id',
        'program_id',
        'price',
        'number',
        'name',
        'status',
    ];

    public function guide()
    {
        return $this->belongsTo(Guide::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function tourists()
    {
        return $this->hasMany(Tourist::class);
    }
}
