<?php

namespace App\Http\Controllers;

use App\Models\box;
use App\Utils\encryptor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class box_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try { 

            $box = box::all();

            if (!$box) {
                return response()->json([
                    'error' =>  "Error al mostrar caja",
                    'success' => false,
                    'message' => 'Error al mostrar',
                    'code' => 400,
                ], 400);
            }

            return response()->json([
                "message" => "Cajas mostratadas exitosamente",
                'success' => true,
                'code' => 200,
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
        try {
            $userId = Auth::id();

            $validator = Validator::make($request->all(), [
                'reference' => 'required',
                'initial_balance' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' =>   implode(' | ', $validator->errors()->all()),
                    'success' => false,
                    'message' => 'Corregir estos detalles',
                    'code' => 400,
                ], 400);
            }

            $validaterData = $validator->validated();
            $validaterData["created_by"] = $userId; //get authenticated user id from token
            $validaterData["status"] = "A";
            $validaterData["opening_date"] = Carbon::now()->format('Y-m-d H:i:s');


            $box = box::create($validaterData);

            if (!$box) {
                return response()->json([
                    'error' =>  "Error al crear la caja",
                    'success' => false,
                    'message' => 'Error en la caja',
                    'code' => 400,
                ], 400);
            }

            return response()->json([
                "message" => "Caja creado exitosamente",
                'success' => true,
                'code' => 200,
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $box = box::find(encryptor::decrypt($id));

            if (!$box) {
                return response()->json([
                    'error' =>  "Error al obtener la caja",
                    'success' => false,
                    'message' => 'Error en la caja',
                    'code' => 400,
                ], 400);
            }

            return response()->json([
                "message" => "Caja creado exitosamente",
                'success' => true,
                'code' => 200,
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {}

    public function close(Request $request)
    {
        try {
            if ($request->input("identifier")) {
                $box = box::find(encryptor::decrypt($request->input("identifier")));

                if (!$box) {
                    return response()->json([
                        'error' =>  "Error al crear la caja",
                        'success' => false,
                        'message' => 'Error en la caja',
                        'code' => 400,
                    ], 400);
                }

                $box->status = "C";
                $box->closing_date = Carbon::now();
                $box->save();
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false,
                'message' => 'Hubo un error al cerrar esta caja',
                'code' => 500,
            ], 500);
        }
    }
}
