<?php

namespace App\Http\Controllers;

use App\Models\TipoHabitacion as TipoHabitacionModel;
use Illuminate\Http\Request;

class TipoHabitacion extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $tipos = TipoHabitacionModel::paginate($perPage);
            
            if ($tipos->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron tipos de habitación',
                    'success' => true
                ], 200);
            }
            
            return response()->json([
                'message' => 'Tipos de habitación obtenidos exitosamente',
                'success' => true,
                'data' => $tipos
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener tipos de habitación',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $tipo = TipoHabitacionModel::find($id);
            
            if (!$tipo) {
                return response()->json([
                    'message' => 'Tipo de habitación no encontrado',
                    'success' => false
                ], 404);
            }
            
            return response()->json([
                'message' => 'Tipo de habitación obtenido exitosamente',
                'success' => true,
                'data' => $tipo
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener tipo de habitación',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validation = $request->validate([
                'nombre' => 'required|string|max:255|unique:tipos_habitacion,nombre',
            ]);
            
            $tipo = TipoHabitacionModel::create($validation);
            
            return response()->json([
                'message' => 'Tipo de habitación creado exitosamente',
                'success' => true,
                'data' => $tipo
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'message' => 'Error de validación',
                'success' => false,
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear tipo de habitación',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $tipo = TipoHabitacionModel::find($id);
            
            if (!$tipo) {
                return response()->json([
                    'message' => 'Tipo de habitación no encontrado',
                    'success' => false
                ], 404);
            }
            
            $validation = $request->validate([
                'nombre' => 'sometimes|string|max:255|unique:tipos_habitacion,nombre,' . $id,
            ]);
            
            $tipo->update($validation);
            
            return response()->json([
                'message' => 'Tipo de habitación actualizado exitosamente',
                'success' => true,
                'data' => $tipo
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'message' => 'Error de validación',
                'success' => false,
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar tipo de habitación',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $tipo = TipoHabitacionModel::find($id);
            
            if (!$tipo) {
                return response()->json([
                    'message' => 'Tipo de habitación no encontrado',
                    'success' => false
                ], 404);
            }
            
            $tipo->delete();
            
            return response()->json([
                'message' => 'Tipo de habitación eliminado exitosamente',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar tipo de habitación',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

