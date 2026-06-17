<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Acomodacion extends Model
{
    use HasFactory;
    protected $table = 'acomodaciones'; 
    protected $fillable = [
        'nombre',
        'descripcion',
    ]; 
    public $timestamps = false;
    public function habitaciones()
    {
        return $this -> hasMany(HotelHabitacion::class);
    }
}
