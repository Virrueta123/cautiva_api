<?php

namespace App\Http\Controllers;

use App\Http\Resources\spending_type\spending_type_all_resource;
use App\Models\spending_type;
use Illuminate\Http\Request;

class spending_type_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // cargar todos los tipos de gastos con try catch
        try {
            $spending_type = spending_type::all();

            if(!$spending_type){
                return response()->json([
                    'error' =>  "No hay registros",
                    'success' => false,
                    'message' => 'Error al cargar tipos de gastos',
                    'code' => 400,
                ], 400);
            }
            return response()->json([
                'error' =>   null,
                'success' => true,
                'message' => 'Tipos de gastos cargado exitosamente',
                'code' => 200,
                'data' => spending_type_all_resource::collection($spending_type),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false,
                'message' => 'Hubo un error al obtener los productos',
                'code' => 500,
            ], 500);
        }
     
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
