<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = 'hoteles';

    protected $fillable = [
        'nombre',
        'direccion',
        'ciudad',
        'nit',
        'numero_habitaciones'
    ];

    public function habitaciones()
    {
        return $this->hasMany(HotelHabitacion::class);
    }
}
