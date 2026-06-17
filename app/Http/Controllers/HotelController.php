<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HotelController extends Controller
{
    public function index()
    {
        $hoteles = Hotel::with('habitaciones.tipoHabitacion', 'habitaciones.acomodacion')->get();
        return response()->json($hoteles);
    }

    public function show($id)
    {
        $hotel = Hotel::with('habitaciones.tipoHabitacion', 'habitaciones.acomodacion')->find($id);
        
        if (!$hotel) {
            return response()->json(['error' => 'Hotel no encontrado'], 404);
        }
        
        return response()->json($hotel);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:200|unique:hoteles,nombre',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'nit' => 'required|string|max:20',
            'numero_habitaciones' => 'required|integer|min:1',
        ], [
            'nombre.unique' => 'El nombre del hotel ya existe en el sistema',
            'numero_habitaciones.min' => 'El número de habitaciones debe ser mayor a 0',
        ]);

        $hotel = Hotel::create($validated);
        
        return response()->json([
            'message' => 'Hotel creado exitosamente',
            'hotel' => $hotel
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $hotel = Hotel::find($id);

        if (!$hotel) {
            return response()->json(['error' => 'Hotel no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:200|unique:hoteles,nombre,' . $id,
            'direccion' => 'sometimes|string|max:255',
            'ciudad' => 'sometimes|string|max:100',
            'nit' => 'sometimes|string|max:20',
            'numero_habitaciones' => 'sometimes|integer|min:1',
        ]);

        if (isset($validated['numero_habitaciones'])) {
            $totalActual = $hotel->habitaciones()->sum('cantidad');
            if ($validated['numero_habitaciones'] < $totalActual) {
                throw ValidationException::withMessages([
                    'numero_habitaciones' => "No se puede reducir el número de habitaciones a {$validated['numero_habitaciones']}. Actualmente hay {$totalActual} habitaciones configuradas."
                ]);
            }
        }

        $hotel->update($validated);

        return response()->json([
            'message' => 'Hotel actualizado exitosamente',
            'hotel' => $hotel
        ]);
    }

    public function destroy($id)
    {
        $hotel = Hotel::find($id);

        if (!$hotel) {
            return response()->json(['error' => 'Hotel no encontrado'], 404);
        }

        $hotel->delete();

        return response()->json(['message' => 'Hotel eliminado exitosamente']);
    }
}
