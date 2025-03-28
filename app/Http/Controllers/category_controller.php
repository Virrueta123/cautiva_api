<?php

namespace App\Http\Controllers;

use App\Http\Resources\category_resource;
use App\Models\category;
use Illuminate\Http\Request;

class category_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try { 
            $categories = category::all();
            if(!$categories){
                return response()->json([
                    'error' =>  "Vuelva a registrar",
                    'success' => false,
                    'message' => 'Error al crear el producto',
                    'code' => 400,
                ], 400);
            }
            return response()->json([
                'error' =>   null,
                'success' => true,
                'message' => 'Producto cargado exitosamente',
                'code' => 200,
                'data' => category_resource::collection($categories),
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
