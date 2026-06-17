<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\HotelHabitacion;
use App\Models\TipoHabitacion;
use App\Models\Acomodacion;

class HotelEjemploSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener los tipos de habitación y acomodaciones
        $estandar = TipoHabitacion::where('nombre', 'ESTANDAR')->first();
        $junior = TipoHabitacion::where('nombre', 'JUNIOR')->first();
        $suite = TipoHabitacion::where('nombre', 'SUITE')->first();

        $sencilla = Acomodacion::where('nombre', 'SENCILLA')->first();
        $doble = Acomodacion::where('nombre', 'DOBLE')->first();
        $triple = Acomodacion::where('nombre', 'TRIPLE')->first();
        $cuadruple = Acomodacion::where('nombre', 'CUADRUPLE')->first();

        // Crear hotel de ejemplo
        $hotel = Hotel::create([
            'nombre' => 'DECAMERON CARTAGENA',
            'direccion' => 'CALLE 23 58-25',
            'ciudad' => 'CARTAGENA',
            'nit' => '12345678-9',
            'numero_habitaciones' => 42,
        ]);

        // Asignar habitaciones según el ejemplo del request
        // 25 Estándar Sencilla
        HotelHabitacion::create([
            'hotel_id' => $hotel->id,
            'tipo_habitacion_id' => $estandar->id,
            'acomodacion_id' => $sencilla->id,
            'cantidad' => 25,
        ]);

        // 12 Junior Triple
        HotelHabitacion::create([
            'hotel_id' => $hotel->id,
            'tipo_habitacion_id' => $junior->id,
            'acomodacion_id' => $triple->id,
            'cantidad' => 12,
        ]);

        // 5 Estándar Doble
        HotelHabitacion::create([
            'hotel_id' => $hotel->id,
            'tipo_habitacion_id' => $estandar->id,
            'acomodacion_id' => $doble->id,
            'cantidad' => 5,
        ]);
    }
}
