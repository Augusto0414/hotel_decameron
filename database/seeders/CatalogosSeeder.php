<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoHabitacion;
use App\Models\Acomodacion;

class CatalogosSeeder extends Seeder
{
    public function run(): void
    {
        TipoHabitacion::create(['nombre' => 'ESTANDAR']);
        TipoHabitacion::create(['nombre' => 'JUNIOR']);
        TipoHabitacion::create(['nombre' => 'SUITE']);

        Acomodacion::create(['nombre' => 'SENCILLA', 'descripcion' => 'Habitación con cama sencilla']);
        Acomodacion::create(['nombre' => 'DOBLE', 'descripcion' => 'Habitación con cama doble']);
        Acomodacion::create(['nombre' => 'TRIPLE', 'descripcion' => 'Habitación con tres camas']);
        Acomodacion::create(['nombre' => 'CUADRUPLE', 'descripcion' => 'Habitación con cuatro camas']);
    }
}
