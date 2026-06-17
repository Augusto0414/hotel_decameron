<?php

namespace App\Http\Controllers;

use App\Models\TipoHabitacion;
use App\Models\Acomodacion;

class CatalogoController extends Controller
{
    public function tiposHabitacion()
    {
        $tipos = TipoHabitacion::all();
        return response()->json($tipos);
    }

    public function acomodaciones()
    {
        $acomodaciones = Acomodacion::all();
        return response()->json($acomodaciones);
    }

    public function catalogo()
    {
        return response()->json([
            'tipos_habitacion' => TipoHabitacion::all(),
            'acomodaciones' => Acomodacion::all(),
        ]);
    }
}
