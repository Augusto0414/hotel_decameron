<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\HotelHabitacion;
use App\Models\TipoHabitacion;
use App\Models\Acomodacion;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HotelHabitacionController extends Controller
{
    private const TIPO_ACOMODACION_RULES = [
        'ESTANDAR' => ['SENCILLA', 'DOBLE'],
        'JUNIOR' => ['TRIPLE', 'CUADRUPLE'],
        'SUITE' => ['SENCILLA', 'DOBLE', 'TRIPLE'],
    ];

    public function indexByHotel($hotelId)
    {
        $hotel = Hotel::find($hotelId);

        if (!$hotel) {
            return response()->json(['error' => 'Hotel no encontrado'], 404);
        }

        $habitaciones = HotelHabitacion::where('hotel_id', $hotelId)
            ->with('tipoHabitacion', 'acomodacion')
            ->get();

        return response()->json([
            'hotel' => $hotel,
            'habitaciones' => $habitaciones,
            'total_configuradas' => $habitaciones->sum('cantidad'),
            'capacidad_disponible' => $hotel->numero_habitaciones - $habitaciones->sum('cantidad'),
        ]);
    }

    public function store(Request $request, $hotelId)
    {
        $hotel = Hotel::find($hotelId);

        if (!$hotel) {
            return response()->json(['error' => 'Hotel no encontrado'], 404);
        }

        $validated = $request->validate([
            'tipo_habitacion_id' => 'required|exists:tipos_habitacion,id',
            'acomodacion_id' => 'required|exists:acomodaciones,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $tipoHabitacion = TipoHabitacion::find($validated['tipo_habitacion_id']);
        $acomodacion = Acomodacion::find($validated['acomodacion_id']);

        $tipoNombre = strtoupper($tipoHabitacion->nombre);
        $acomodacionNombre = strtoupper($acomodacion->nombre);

        if (!isset(self::TIPO_ACOMODACION_RULES[$tipoNombre])) {
            return response()->json([
                'error' => "Tipo de habitación '{$tipoNombre}' no es válido"
            ], 422);
        }

        if (!in_array($acomodacionNombre, self::TIPO_ACOMODACION_RULES[$tipoNombre])) {
            return response()->json([
                'error' => "La acomodación '{$acomodacionNombre}' no es válida para el tipo '{$tipoNombre}'. Acomodaciones válidas: " . implode(', ', self::TIPO_ACOMODACION_RULES[$tipoNombre])
            ], 422);
        }

        $existe = HotelHabitacion::where('hotel_id', $hotelId)
            ->where('tipo_habitacion_id', $validated['tipo_habitacion_id'])
            ->where('acomodacion_id', $validated['acomodacion_id'])
            ->first();

        if ($existe) {
            return response()->json([
                'error' => "Esta combinación de tipo de habitación y acomodación ya existe para este hotel"
            ], 422);
        }

        $totalActual = $hotel->habitaciones()->sum('cantidad');
        $nuevoTotal = $totalActual + $validated['cantidad'];

        if ($nuevoTotal > $hotel->numero_habitaciones) {
            return response()->json([
                'error' => "La cantidad total de habitaciones ({$nuevoTotal}) superaría la capacidad del hotel ({$hotel->numero_habitaciones}). Disponible: " . ($hotel->numero_habitaciones - $totalActual)
            ], 422);
        }

        $hotelHabitacion = HotelHabitacion::create([
            'hotel_id' => $hotelId,
            'tipo_habitacion_id' => $validated['tipo_habitacion_id'],
            'acomodacion_id' => $validated['acomodacion_id'],
            'cantidad' => $validated['cantidad'],
        ]);

        return response()->json([
            'message' => 'Habitaciones asignadas exitosamente',
            'hotel_habitacion' => $hotelHabitacion->load('tipoHabitacion', 'acomodacion'),
            'total_configuradas' => $hotel->habitaciones()->sum('cantidad') + $validated['cantidad'],
            'capacidad_disponible' => $hotel->numero_habitaciones - ($hotel->habitaciones()->sum('cantidad') + $validated['cantidad']),
        ], 201);
    }

    public function update(Request $request, $hotelId, $habitacionId)
    {
        $hotel = Hotel::find($hotelId);

        if (!$hotel) {
            return response()->json(['error' => 'Hotel no encontrado'], 404);
        }

        $hotelHabitacion = HotelHabitacion::where('id', $habitacionId)
            ->where('hotel_id', $hotelId)
            ->first();

        if (!$hotelHabitacion) {
            return response()->json(['error' => 'Asignación de habitación no encontrada'], 404);
        }

        $validated = $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $totalOtras = $hotel->habitaciones()
            ->where('id', '!=', $habitacionId)
            ->sum('cantidad');
        
        $nuevoTotal = $totalOtras + $validated['cantidad'];

        if ($nuevoTotal > $hotel->numero_habitaciones) {
            return response()->json([
                'error' => "La nueva cantidad total ({$nuevoTotal}) superaría la capacidad del hotel ({$hotel->numero_habitaciones}). Disponible: " . ($hotel->numero_habitaciones - $totalOtras)
            ], 422);
        }

        $hotelHabitacion->update(['cantidad' => $validated['cantidad']]);

        return response()->json([
            'message' => 'Asignación de habitaciones actualizada exitosamente',
            'hotel_habitacion' => $hotelHabitacion->load('tipoHabitacion', 'acomodacion'),
            'total_configuradas' => $nuevoTotal,
            'capacidad_disponible' => $hotel->numero_habitaciones - $nuevoTotal,
        ]);
    }

    public function destroy($hotelId, $habitacionId)
    {
        $hotel = Hotel::find($hotelId);

        if (!$hotel) {
            return response()->json(['error' => 'Hotel no encontrado'], 404);
        }

        $hotelHabitacion = HotelHabitacion::where('id', $habitacionId)
            ->where('hotel_id', $hotelId)
            ->first();

        if (!$hotelHabitacion) {
            return response()->json(['error' => 'Asignación de habitación no encontrada'], 404);
        }

        $hotelHabitacion->delete();

        return response()->json(['message' => 'Asignación de habitaciones eliminada exitosamente']);
    }
}
