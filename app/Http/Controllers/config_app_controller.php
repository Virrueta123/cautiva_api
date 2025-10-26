<?php
namespace App\Http\Controllers;

use App\Models\config_app;
use App\Services\config_service;

class config_app_controller extends Controller
{
    //cargar las configuraciones de la aplicacion
    public function index()
    {
        try {
            $configService = new config_service();
             
           $configService->validarClaves(['promocion_general']);

            $valores = $configService->obtenerValoresConvertidos();
         
            if ($valores) {
                return response()->json([
                    'error'   => null,
                    'success' => true,
                    'message' => 'Configuraciones cargadas exitosamente',
                    'code'    => 200,
                    'data'    =>  $valores,
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json([
                'error'   => $e->getMessage(),
                'success' => false,
                'message' => 'Hubo un error al obtener las configuraciones',
                'code'    => 500,
            ], 500);
        }

    }
}
