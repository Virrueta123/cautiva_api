<?php

namespace App\Http\Controllers;

use App\Models\payment;
use Illuminate\Http\Request;

class report_controller extends Controller
{
    /**
     * Para enviar los reportes de las ventas
     */
    public function report_sale(string $period)
    {
        try {

            switch ($period) {
                case 'Anual':
                    $anios = payment::selectRaw('DISTINCT YEAR(created_at) AS label')
                        ->where('type_payment', 'VENTA')
                        ->orderBy('label')
                        ->get();


                    /*[SUCCESSFULLY]*/
                    return response()->json([
                        'error' =>   null,
                        'success' => true,
                        'message' => 'Reporte generado exitosamente',
                        'code' => 200,
                        'data' => $anios,
                    ], 200);
                    break;
                case 'week':

                    break;
                case 'day':

                    break;
                case 'hour':

                    break;
                case 'minute':

                    break;
            }
        } catch (\Exception $th) {
            $code = 401;
            return response()->json([
                'error' => $th->getMessage(),
                'success' => false,
                'message' => 'error al crear el comprobante',
                'code' => $code,
            ], $code);
        }
    }

    public function report_sale_year_data(string $year, string $type_sale)
    {
        try {

            $array_mensual = array();

            $anio = $year; // Año seleccionado

            // Array con los nombres de los meses en español
            $meses_es = [
                1 => "Enero",
                2 => "Febrero",
                3 => "Marzo",
                4 => "Abril",
                5 => "Mayo",
                6 => "Junio",
                7 => "Julio",
                8 => "Agosto",
                9 => "Septiembre",
                10 => "Octubre",
                11 => "Noviembre",
                12 => "Diciembre"
            ];

            for ($mes = 1; $mes <= 12; $mes++) {
                $inicio_mes = date('Y-m-01', strtotime("$anio-$mes-01"));
                $fin_mes = date('Y-m-t', strtotime("$anio-$mes-01"));

                $ventas = payment::where('type_payment', 'VENTA')
                    ->whereBetween('created_at', [$inicio_mes, $fin_mes])
                    ->get();

                if ($type_sale == 'quantity') {
                    $ventas = $ventas->count();
                } else if ($type_sale == 'amount') {
                    $ventas = $ventas->sum('amount');
                }

                /*[LOAD DATA]*/
                array_push($array_mensual, [
                    'label' => $meses_es[$mes], // Nombre del mes en español
                    'value' => strval($ventas)
                ]);
            }

            return response()->json([
                'error' =>   null,
                'success' => true,
                'message' => 'Reporte generado exitosamente',
                'code' => 200,
                'data' => $array_mensual,
            ], 200);
        } catch (\Exception $th) {
            $code = 401;
            return response()->json([
                'error' => $th->getMessage(),
                'success' => false,
                'message' => 'error al crear el comprobante',
                'code' => $code,
            ], $code);
        }
    }
}
