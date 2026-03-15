<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'status',
        'connector_type',
        'power_kw',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
