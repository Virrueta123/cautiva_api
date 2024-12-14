<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class api_peru_dev_controller extends Controller
{
    public function lookup_dni($dni)
    {
        try {
            // dd($dni);
            if(!$dni){
                return response()->json([
                    'error' =>  "DNI no encontrado",    
                    'success' => false,
                    'message' => 'Error al encontrar el dni',
                    'code' => 400,
                ], 400);
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->withToken('970a9e3b884e59eb21e03786ad93fb685e3ef828653d19b7e15687d214f86356')
            ->post('https://apiperu.dev/api/dni', [
                'dni' => $dni
            ]);

            $datax = $response->json(); 

            // Check if the request was successful
            if ($response->successful()) { 
                return response()->json([
                    'error' =>   null,
                    'success' => true,
                    'message' => 'Dni encontrado exitosamente',
                    'code' => 200,
                    'data' => array( 
                        'name' => $datax["data"]['nombres'],
                        'lastname' => $datax["data"]['apellido_paterno']." ". $datax["data"]['apellido_materno'],
                    ),
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Dni no encontrado',
                    'success' => false,
                    'message' => 'Error al encontrar el dni',
                    'code' => 401, 
                ],401);
            }
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false,
                'message' => 'Error al encontrar el dni',
                'code' => 500,
            ], 500);
        }
    }

    public function lookup_ruc($ruc)
    {
        try {
           
            if(!$ruc){
                return response()->json([
                    'error' =>  "RUC no encontrado",    
                    'success' => false,
                    'message' => 'Error al encontrar el ruc',
                    'code' => 400,
                ], 400);
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->withToken('970a9e3b884e59eb21e03786ad93fb685e3ef828653d19b7e15687d214f86356')
            ->post('https://apiperu.dev/api/ruc', [
                'ruc' => $ruc
            ]);
 

            $datax = $response->json(); 

            // Check if the request was successful
            if ($response->successful()) { 
                return response()->json([
                    'error' =>   null,
                    'success' => true,
                    'message' => 'Ruc encontrado exitosamente',
                    'code' => 200,
                    'data' => array( 
                        'bussiness_name' => $datax["data"]['nombre_o_razon_social'], 
                    ),
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Ruc no encontrado',
                    'success' => false,
                    'message' => 'Error al encontrar el ruc',
                    'code' => 401, 
                ],401);
            }
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false,
                'message' => 'Error al encontrar el ruc',
                'code' => 500,
            ], 500);
        }
    }
}
