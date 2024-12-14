<?php

namespace App\Http\Controllers;

use App\Http\Resources\account\account_select_resource;
use App\Models\account;
use Illuminate\Http\Request;

class account_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function select(){
        try { 
            $account = account::all();
            if(!$account){
                return response()->json([
                    'error' =>  "no hay registros",
                    'success' => false,
                    'message' => 'Error al cargar cuentas',
                    'code' => 400,
                ], 400);
            }
            return response()->json([
                'error' =>   null,
                'success' => true,
                'message' => 'Cuentas cargado exitosamente',
                'code' => 200,
                'data' => account_select_resource::collection($account),
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
