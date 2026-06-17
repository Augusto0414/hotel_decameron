<?php

namespace App\Http\Controllers;

use App\Models\Hotel as HotelModel;
use Illuminate\Http\Request;

class Hotel extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $hoteles = HotelModel::with('habitaciones.tipoHabitacion', 'habitaciones.acomodacion')->paginate($perPage);
            
            if ($hoteles->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron hoteles',
                    'success' => true
                ], 200);
            }
            
            return response()->json([
                'message' => 'Hoteles obtenidos exitosamente',
                'success' => true,
                'data' => $hoteles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los hoteles',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $hotel = HotelModel::with('habitaciones.tipoHabitacion', 'habitaciones.acomodacion')->find($id);
            
            if (!$hotel) {
                return response()->json([
                    'message' => 'Hotel no encontrado',
                    'success' => false
                ], 404);
            }
            
            return response()->json([
                'message' => 'Hotel obtenido exitosamente',
                'success' => true,
                'data' => $hotel
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el hotel',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validation = $request->validate([
                'nombre' => 'required|string|max:200|unique:hoteles,nombre',
                'direccion' => 'required|string|max:255',
                'ciudad' => 'required|string|max:100',
                'nit' => 'required|string|max:20',
                'numero_habitaciones' => 'required|integer|min:1',
            ]);
            
            $hotel = HotelModel::create($validation);
            
            return response()->json([
                'message' => 'Hotel creado exitosamente',
                'success' => true,
                'data' => $hotel
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'message' => 'Error de validación',
                'success' => false,
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el hotel',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $hotel = HotelModel::find($id);
            
            if (!$hotel) {
                return response()->json([
                    'message' => 'Hotel no encontrado',
                    'success' => false
                ], 404);
            }
            
            $validation = $request->validate([
                'nombre' => 'sometimes|string|max:200|unique:hoteles,nombre,' . $id,
                'direccion' => 'sometimes|string|max:255',
                'ciudad' => 'sometimes|string|max:100',
                'nit' => 'sometimes|string|max:20',
                'numero_habitaciones' => 'sometimes|integer|min:1',
            ]);
            
            if (isset($validation['numero_habitaciones'])) {
                $totalActual = $hotel->habitaciones()->sum('cantidad');
                if ($validation['numero_habitaciones'] < $totalActual) {
                    return response()->json([
                        'message' => 'No se puede reducir el número de habitaciones por debajo de las configuradas',
                        'success' => false
                    ], 422);
                }
            }
            
            $hotel->update($validation);
            
            return response()->json([
                'message' => 'Hotel actualizado exitosamente',
                'success' => true,
                'data' => $hotel
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'message' => 'Error de validación',
                'success' => false,
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el hotel',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $hotel = HotelModel::find($id);
            
            if (!$hotel) {
                return response()->json([
                    'message' => 'Hotel no encontrado',
                    'success' => false
                ], 404);
            }
            
            $hotel->delete();
            
            return response()->json([
                'message' => 'Hotel eliminado exitosamente',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el hotel',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
