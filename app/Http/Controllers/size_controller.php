<?php

namespace App\Http\Controllers;

use App\Http\Resources\size\select_size_resource;
use App\Models\size;
use Illuminate\Http\Request;

class size_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try { 
            $size = size::all();
            if(!$size){
                return response()->json([
                    'error' =>  "Error al carga los datos",
                    'success' => false,
                    'message' => 'Error al crear el producto',
                    'code' => 400,
                ], 400);
            }
            return response()->json([
                'error' =>   null,
                'success' => true,
                'message' => 'Tamaños cargado exitosamente',
                'code' => 200,
                'data' => select_size_resource::collection($size),
            ], 200);
            
        } catch (\Throwable $th) {
            $code = 401;
            return response()->json([
                'error' => $th->getMessage(),
                'success' => false,
                'message' => 'Error al crear el producto',
                'code' => $code,
            ], $code);
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
