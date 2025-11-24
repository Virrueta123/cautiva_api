<?php
namespace App\Services;

class print_services
{

    private $url = 'http://print_script.test/impimir_voucher_cautiva_comprobante';

    public function print_voucher($serie, $correlativo, $fecha, $cliente, $detalle, $subtotal, $descuento, $total)
    {
 
        try {
            // URL a la que deseas hacer la solicitud
            // $url = 'https://fowl-sacred-strangely.ngrok-free.app/print_script/public/ipc';
            $url = $this->url;

            // Datos que deseas enviar en la solicitud POST
            $postData = array(
                'serie'      => $serie,
                'correlativo'  => $correlativo,
                "fecha"      => $fecha,
                "cliente"      => $cliente,
                'detalles'      => json_encode($detalle),
                'subtotal'      => $subtotal,
                'descuento'      => $descuento,
                'total'      => $total,
            );

            // Inicializar cURL
            $ch = curl_init();

            // Establecer la URL de la solicitud
            curl_setopt($ch, CURLOPT_URL, $url);

            // Establecer el método de la solicitud como POST
            curl_setopt($ch, CURLOPT_POST, true);

            // Convertir los datos a formato de cadena y establecerlos como datos de POST
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

            // Si necesitas agregar encabezados u otros datos a la solicitud, aquí es donde lo harías
            // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer TOKEN'));

            // Establecer que deseas recibir la respuesta como una cadena en lugar de imprimirla directamente
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Ejecutar la solicitud y obtener la respuesta
            $response = curl_exec($ch);

            // Verificar si hubo errores
            if (curl_errno($ch)) {
                echo 'Error: ' . curl_error($ch);
            }

            // Cerrar la conexión cURL
            curl_close($ch);

          

        } catch (\Exception $e) {
            echo $e->getMessage();
        }       
   
    }

}
