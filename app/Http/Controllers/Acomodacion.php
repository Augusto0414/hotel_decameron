<?php

namespace App\Http\Controllers;

use App\Models\Acomodacion as AcomodacionModel;
use Illuminate\Http\Request;

class Acomodacion extends Controller
{
   public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $acomodacion = AcomodacionModel::paginate($perPage);
            
            if ($acomodacion->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron acomodaciones',
                    'success' => true
                ], 200);
            }
            
            return response()->json([
                'message' => 'Acomodaciones obtenidas exitosamente',
                'success' => true,
                'data' => $acomodacion
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las acomodaciones',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
 public function show($id)
    {
        try {
            $acomodacion = AcomodacionModel::find($id);
            
            if (!$acomodacion) {
                return response()->json([
                    'message' => 'Acomodación no encontrada',
                    'success' => false
                ], 404);
            }
            
            return response()->json([
                'message' => 'Acomodación obtenida exitosamente',
                'success' => true,
                'data' => $acomodacion
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener la acomodación',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
 public function store(Request $request)
{
    try {
        $validation = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);
        
        $acomodacion = AcomodacionModel::create($validation);
        
        return response()->json([
            'message' => 'Acomodación creada exitosamente',
            'success' => true,
            'data' => $acomodacion
        ], 201);
    } catch (\Illuminate\Validation\ValidationException $ve) {
        return response()->json([
            'message' => 'Error de validación',
            'success' => false,
            'errors' => $ve->errors()
        ], 422);
    } catch (\Throwable $th) {
        return response()->json([
            'message' => 'Error al crear la acomodación',
            'success' => false,
            'error' => $th->getMessage()
        ], 500);
    }
}

public function update(Request $request, $id)
{
    try {
        $acomodacion = AcomodacionModel::find($id);
        
        if (!$acomodacion) {
            return response()->json([
                'message' => 'Acomodación no encontrada',
                'success' => false
            ], 404);
        }
        
        $validation = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
        ]);
        
        $acomodacion->update($validation);
        
        return response()->json([
            'message' => 'Acomodación actualizada exitosamente',
            'success' => true,
            'data' => $acomodacion
        ], 200);
    } catch (\Illuminate\Validation\ValidationException $ve) {
        return response()->json([
            'message' => 'Error de validación',
            'success' => false,
            'errors' => $ve->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al actualizar la acomodación',
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

public function destroy($id)
{
    try {
        $acomodacion = AcomodacionModel::find($id);
        
        if (!$acomodacion) {
            return response()->json([
                'message' => 'Acomodación no encontrada',
                'success' => false
            ], 404);
        }
        
        $acomodacion->delete();
        
        return response()->json([
            'message' => 'Acomodación eliminada exitosamente',
            'success' => true
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al eliminar la acomodación',
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}
}
