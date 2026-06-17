<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoHabitacion extends Model
{
    protected $table = 'tipos_habitacion';

    protected $fillable = [
        'nombre'
    ];

    public $timestamps = false;

    public function habitaciones()
    {
        return $this->hasMany(HotelHabitacion::class);
    }
}
