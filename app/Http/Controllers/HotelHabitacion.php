<?php

namespace App\Http\Controllers;

use App\Models\HotelHabitacion as HotelHabitacionModel;
use App\Models\Hotel;
use App\Models\TipoHabitacion;
use App\Models\Acomodacion;
use Illuminate\Http\Request;

class HotelHabitacion extends Controller
{
    private const TIPO_ACOMODACION_RULES = [
        'ESTANDAR' => ['SENCILLA', 'DOBLE'],
        'JUNIOR' => ['TRIPLE', 'CUADRUPLE'],
        'SUITE' => ['SENCILLA', 'DOBLE', 'TRIPLE'],
    ];

    public function indexByHotel($hotelId)
    {
        try {
            $hotel = Hotel::find($hotelId);

            if (!$hotel) {
                return response()->json([
                    'message' => 'Hotel no encontrado',
                    'success' => false
                ], 404);
            }

            $habitaciones = HotelHabitacionModel::where('hotel_id', $hotelId)
                ->with('tipoHabitacion', 'acomodacion')
                ->get();

            return response()->json([
                'message' => 'Habitaciones obtenidas exitosamente',
                'success' => true,
                'hotel' => $hotel,
                'habitaciones' => $habitaciones,
                'total_configuradas' => $habitaciones->sum('cantidad'),
                'capacidad_disponible' => $hotel->numero_habitaciones - $habitaciones->sum('cantidad'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener habitaciones',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request, $hotelId)
    {
        try {
            $hotel = Hotel::find($hotelId);

            if (!$hotel) {
                return response()->json([
                    'message' => 'Hotel no encontrado',
                    'success' => false
                ], 404);
            }

            $validation = $request->validate([
                'tipo_habitacion_id' => 'required|exists:tipos_habitacion,id',
                'acomodacion_id' => 'required|exists:acomodaciones,id',
                'cantidad' => 'required|integer|min:1',
            ]);

            $tipoHabitacion = TipoHabitacion::find($validation['tipo_habitacion_id']);
            $acomodacion = Acomodacion::find($validation['acomodacion_id']);

            $tipoNombre = strtoupper($tipoHabitacion->nombre);
            $acomodacionNombre = strtoupper($acomodacion->nombre);

            if (!isset(self::TIPO_ACOMODACION_RULES[$tipoNombre])) {
                return response()->json([
                    'message' => "Tipo de habitación '{$tipoNombre}' no válido",
                    'success' => false
                ], 422);
            }

            if (!in_array($acomodacionNombre, self::TIPO_ACOMODACION_RULES[$tipoNombre])) {
                return response()->json([
                    'message' => "Acomodación '{$acomodacionNombre}' no válida para '{$tipoNombre}'",
                    'success' => false
                ], 422);
            }

            $existe = HotelHabitacionModel::where('hotel_id', $hotelId)
                ->where('tipo_habitacion_id', $validation['tipo_habitacion_id'])
                ->where('acomodacion_id', $validation['acomodacion_id'])
                ->first();

            if ($existe) {
                return response()->json([
                    'message' => 'Esta combinación ya existe para este hotel',
                    'success' => false
                ], 422);
            }

            $totalActual = $hotel->habitaciones()->sum('cantidad');
            $nuevoTotal = $totalActual + $validation['cantidad'];

            if ($nuevoTotal > $hotel->numero_habitaciones) {
                return response()->json([
                    'message' => "Supera la capacidad. Disponible: " . ($hotel->numero_habitaciones - $totalActual),
                    'success' => false
                ], 422);
            }

            $hotelHabitacion = HotelHabitacionModel::create([
                'hotel_id' => $hotelId,
                'tipo_habitacion_id' => $validation['tipo_habitacion_id'],
                'acomodacion_id' => $validation['acomodacion_id'],
                'cantidad' => $validation['cantidad'],
            ]);

            return response()->json([
                'message' => 'Habitaciones asignadas exitosamente',
                'success' => true,
                'data' => $hotelHabitacion->load('tipoHabitacion', 'acomodacion'),
                'total_configuradas' => $nuevoTotal,
                'capacidad_disponible' => $hotel->numero_habitaciones - $nuevoTotal,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'message' => 'Error de validación',
                'success' => false,
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al asignar habitaciones',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $hotelId, $habitacionId)
    {
        try {
            $hotel = Hotel::find($hotelId);

            if (!$hotel) {
                return response()->json([
                    'message' => 'Hotel no encontrado',
                    'success' => false
                ], 404);
            }

            $hotelHabitacion = HotelHabitacionModel::where('id', $habitacionId)
                ->where('hotel_id', $hotelId)
                ->first();

            if (!$hotelHabitacion) {
                return response()->json([
                    'message' => 'Asignación no encontrada',
                    'success' => false
                ], 404);
            }

            $validation = $request->validate([
                'cantidad' => 'required|integer|min:1',
            ]);

            $totalOtras = $hotel->habitaciones()
                ->where('id', '!=', $habitacionId)
                ->sum('cantidad');
            
            $nuevoTotal = $totalOtras + $validation['cantidad'];

            if ($nuevoTotal > $hotel->numero_habitaciones) {
                return response()->json([
                    'message' => "Supera la capacidad. Disponible: " . ($hotel->numero_habitaciones - $totalOtras),
                    'success' => false
                ], 422);
            }

            $hotelHabitacion->update(['cantidad' => $validation['cantidad']]);

            return response()->json([
                'message' => 'Asignación actualizada exitosamente',
                'success' => true,
                'data' => $hotelHabitacion->load('tipoHabitacion', 'acomodacion'),
                'total_configuradas' => $nuevoTotal,
                'capacidad_disponible' => $hotel->numero_habitaciones - $nuevoTotal,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'message' => 'Error de validación',
                'success' => false,
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar asignación',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($hotelId, $habitacionId)
    {
        try {
            $hotel = Hotel::find($hotelId);

            if (!$hotel) {
                return response()->json([
                    'message' => 'Hotel no encontrado',
                    'success' => false
                ], 404);
            }

            $hotelHabitacion = HotelHabitacionModel::where('id', $habitacionId)
                ->where('hotel_id', $hotelId)
                ->first();

            if (!$hotelHabitacion) {
                return response()->json([
                    'message' => 'Asignación no encontrada',
                    'success' => false
                ], 404);
            }

            $hotelHabitacion->delete();

            return response()->json([
                'message' => 'Asignación eliminada exitosamente',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar asignación',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

