<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoHabitacion;
use App\Models\Acomodacion;

class CatalogoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'ESTANDAR'],
            ['nombre' => 'JUNIOR'],
            ['nombre' => 'SUITE'],
        ];

        foreach ($tipos as $tipo) {
            TipoHabitacion::firstOrCreate($tipo);
        }

        $acomodaciones = [
            ['nombre' => 'SENCILLA', 'descripcion' => 'Habitación con cama sencilla'],
            ['nombre' => 'DOBLE', 'descripcion' => 'Habitación con cama doble'],
            ['nombre' => 'TRIPLE', 'descripcion' => 'Habitación con tres camas'],
            ['nombre' => 'CUADRUPLE', 'descripcion' => 'Habitación con cuatro camas'],
        ];

        foreach ($acomodaciones as $acomodacion) {
            Acomodacion::firstOrCreate(['nombre' => $acomodacion['nombre']], $acomodacion);
        }
    }
}
