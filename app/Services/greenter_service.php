<?php

namespace App\Services;

use Exception;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;
use Greenter\See;
use Greenter\XMLSecLibs\Certificate\X509Certificate;
use Greenter\XMLSecLibs\Certificate\X509ContentType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Luecano\NumeroALetras\NumeroALetras;

class greenter_service
{
    protected $see;

    public function __construct(See $see)
    {
        $this->see = $see;
    }

    public function convertCertificate()
    {

        $pfx = file_get_contents('certificates/production/certificado.p12');
        $password = 'CautivaModaEstilo123';

        $certificate = new X509Certificate($pfx, $password);
        $pemContent = $certificate->export(X509ContentType::PEM);

        // Guardar como archivo PEM
        file_put_contents('certificates/production/certificado.pem', $pemContent);

        // Configurar en el objeto See (de Greenter)
        $see = new See();
        $see->setCertificate($pemContent);
    }

    public function createCompany()
    {
        return (new Company())
            ->setRuc(config('greenter.ruc'))
            ->setRazonSocial(config('greenter.razon_social'))
            ->setNombreComercial(config('greenter.nombre_comercial'))
            ->setAddress((new Address())
                    ->setUbigueo(config('greenter.address.ubigeo'))
                    ->setDepartamento(config('greenter.address.departamento'))
                    ->setProvincia(config('greenter.address.provincia'))
                    ->setDistrito(config('greenter.address.distrito'))
                    ->setUrbanizacion(config('greenter.address.urbanizacion'))
                    ->setDireccion(config('greenter.address.direccion'))
                    ->setCodLocal("0000")
            );
    }

    public function createClientInvoice($by, $documento, $razonSocial)
    {
        return (new Client())
            ->setTipoDoc($by == "F" ? '6' : "1")
            ->setNumDoc($documento)
            ->setRznSocial($razonSocial);
    }

    public function unsubscribeTicket($correlativo, $motivo, $serie)
    {
        try {
            Log::error($correlativo);
            Log::error($motivo);
            Log::error($serie);
            // Crear el documento de baja
            $baja = (new Voided())
                ->setCorrelativo($correlativo)
                ->setFecComunicacion(new \DateTime())
                ->setFecGeneracion(new \DateTime())
                ->setCompany($this->createCompany());

            // Agregar el detalle de la boleta a dar de baja
            $detalle = (new VoidedDetail())
                ->setTipoDoc('03') // 03 es el código para Boleta
                ->setSerie($serie)
                ->setCorrelativo($correlativo)
                ->setDesMotivoBaja($motivo);

            $baja->setDetails([$detalle]);

            // Enviar el documento de baja
            $result = $this->see->send($baja);

            // Procesar la respuesta
            if ($result->isSuccess()) {
                return [
                    'success' => true,
                    'message' => 'Baja enviada con éxito',
                    'code' => 200,
                    'observations' => "Ticket: " . $result->getTicket()
                ];

                $result = $this->see->getStatus($result->getTicket());
                if ($result->isSuccess()) {
                    return [
                        'success' => true,
                        'message' => 'Baja enviada con éxito',
                        'code' => 200,
                        'observations' => "Estado: " . $result->getCdrResponse()->getDescription()
                    ];
                } else {

                    echo "Error al consultar ticket: " . $result->getError()->getCode() . "\n";
                }
            } else {
                Log::error("Error: " . $result->getError()->getCode());
                return [
                    'success' => false,
                    'message' => "Error: " . $result->getError()->getCode(),
                    'code' => $result->getError()->getCode()
                ];

                // echo $result->getError()->getMessage() . "\n";
            }
        } catch (\Exception $e) {
            Log::error($e);
            return [
                'success' => false,
                'message' => 'Hubo un error al enviar el ticket',
                'code' => 500,
            ];
        }
    }

    //crear un factura
    public function createInvoice($client, $items, $serie, $correlativo, $total)
    {
        try {
            $invoice = (new Invoice())
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Venta - Catalog. 51
                ->setTipoDoc('01') // Factura - Catalog. 01
                ->setSerie($serie)
                ->setCorrelativo($correlativo)
                ->setFechaEmision(new \DateTime()) // Zona horaria: Lima
                ->setFormaPago(new FormaPagoContado()) // FormaPago: Contado
                ->setTipoMoneda('PEN') // Sol - Catalog. 02
                ->setCompany($this->createCompany())
                ->setClient($client)
                ->setMtoOperExoneradas($total)
                ->setMtoIGV(0)
                ->setTotalImpuestos(0)
                ->setValorVenta($total)
                ->setSubTotal($total)
                ->setMtoImpVenta($total);

            $SaleDetail = [];

            foreach ($items as $item) {
                array_push(
                    $SaleDetail,
                    (new SaleDetail())
                        ->setCodProducto($item["CodProducto"])
                        ->setUnidad("NIU")
                        ->setCantidad($item["Cantidad"])
                        ->setDescripcion($item['Descripcion'])
                        ->setMtoBaseIgv($item['MtoValorVenta'])
                        ->setPorcentajeIgv(0)
                        ->setIgv(0)
                        ->setTipAfeIgv('20')
                        ->setTotalImpuestos(0)
                        ->setMtoValorVenta($item['MtoValorVenta'])
                        ->setMtoValorUnitario($item['MtoPrecioUnitario'])
                        ->setMtoPrecioUnitario($item['MtoPrecioUnitario'])
                );
            }

            $formatter = new NumeroALetras();

            $deletreo = 'SON: ' . $formatter->toWords($total) . ' Y 00/100 SOLES';

            $invoice->setDetails($SaleDetail)
                ->setLegends([
                    (new Legend())
                        ->setCode('1000')
                        ->setValue($deletreo)
                ]);

            return $invoice;
        } catch (\Exception $e) {
            Log::error($e);
            return [
                'success' => false,
                'message' => 'Error al crear boleta',
                'code' => 500,
            ];
        }
    }

    public function sendInvoice($invoice)
    {
        $result = $this->see->send($invoice);

        // Guardar XML firmado digitalmente. 
        Storage::disk('local')->put('public/comprobantes/facturas/' . $invoice->getName() . '.xml', $this->see->getFactory()->getLastXml());

        // Verificar si la conexión con SUNAT fue exitosa.
        if (!$result->isSuccess()) {
            $errorCode = $result->getError()->getCode();
            $errorMessage = $result->getError()->getMessage();

            // Si el error es relacionado con problemas del servidor de SUNAT
            if (in_array($errorCode, ['001', '502', '503', '504', '505'])) { // Ejemplo de códigos comunes de error de servidor

                return [
                    'success' => false,
                    'message' => "Error de conexión con SUNAT: $errorMessage",
                    'code' => $errorCode
                ];
            } else {
                // Manejo de otros errores 
                return [
                    'success' => false,
                    'message' => $errorMessage,
                    'code' => $errorCode
                ];
            }
        }

        // Guardar el CDR 
        Storage::disk('local')->put('public/comprobantes/facturas/R-' . $invoice->getName() . '.zip', $result->getCdrZip());


        $cdr = $result->getCdrResponse();
        $code = (int)$cdr->getCode();

        if ($code === 0) {
            $observations = count($cdr->getNotes()) > 0 ? implode(", ", $cdr->getNotes()) : 'Sin observaciones';

            return [
                'success' => true,
                'message' => 'Factura procesada con éxito.',
                'code' => $code,
                'observations' => $observations
            ];
        } elseif ($code >= 2000 && $code <= 3999) {
            return [
                'success' => false,
                'message' => 'ESTADO: RECHAZADA',
                'code' => $code
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Excepción en el CDR recibido.',
                'code' => $code
            ];
        }
    }

    //crear boleta electronica

    public function createTicket($client, $items, $serie, $correlativo, $total)
    {

        try {
            Log::info($correlativo);
            $invoice = (new Invoice())
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Venta - Catalog. 51
                ->setTipoDoc('03') // Factura - Catalog. 01
                ->setSerie($serie)
                ->setCorrelativo($correlativo)
                ->setFechaEmision(new \DateTime()) // Zona horaria: Lima
                ->setTipoMoneda('PEN') // Sol - Catalog. 02
                ->setClient($client)
                ->setMtoOperExoneradas($total)
                ->setMtoIGV(0.0)
                ->setTotalImpuestos(0.0)
                ->setValorVenta($total)
                ->setSubTotal($total)
                ->setMtoImpVenta($total)
                ->setCompany($this->createCompany());

            $SaleDetail = [];

            foreach ($items as $item) {

                array_push(
                    $SaleDetail,
                    (new SaleDetail())
                        ->setCodProducto($item["CodProducto"])
                        ->setUnidad("NIU")
                        ->setCantidad($item["Cantidad"])
                        ->setDescripcion($item['Descripcion'])
                        ->setMtoBaseIgv($item['MtoValorVenta'])
                        ->setPorcentajeIgv(0)
                        ->setIgv(0)
                        ->setTipAfeIgv('20')
                        ->setTotalImpuestos(0)
                        ->setMtoValorVenta($item['MtoValorVenta'])
                        ->setMtoValorUnitario($item['MtoPrecioUnitario'])
                        ->setMtoPrecioUnitario($item['MtoPrecioUnitario'])
                );
            }
            $formatter = new NumeroALetras();

            $deletreo = 'SON: ' . $formatter->toWords($total) . ' Y 00/100 SOLES';

            $invoice->setDetails($SaleDetail)
                ->setLegends([
                    (new Legend())
                        ->setCode('1000')
                        ->setValue($deletreo)
                ]);

            return $invoice;
        } catch (\Exception $e) {
            Log::error($e);
            return [
                'success' => false,
                'message' => 'Error al crear boleta',
                'code' => 500,
            ];
        }
    }

    public function sendTicket($invoice)
    {
        try {
            $result = $this->see->send($invoice);

            // Guardar XML firmado digitalmente.
            Storage::disk('local')->put('public/comprobantes/boletas/' . $invoice->getName() . '.xml', $this->see->getFactory()->getLastXml());

            // Verificamos que la conexión con SUNAT fue exitosa.
            if (!$result->isSuccess()) {
                $errorCode = $result->getError()->getCode();
                $errorMessage = $result->getError()->getMessage();

                // Si el error es relacionado con problemas del servidor de SUNAT
                if (in_array($errorCode, ['001', '502', '503', '504', '505'])) { // Ejemplo de códigos comunes de error de servidor
                    return [
                        'success' => false,
                        'message' => "Error de conexión con SUNAT: $errorMessage",
                        'code' => $errorCode
                    ];
                } else {
                    // Manejo de otros errores 
                    return [
                        'success' => false,
                        'message' => $errorMessage,
                        'code' => $errorCode
                    ];
                }
            }

            // Guardamos el CDR
            Storage::disk('local')->put('public/comprobantes/boletas/R-' . $invoice->getName() . '.zip', $result->getCdrZip());

            $cdr = $result->getCdrResponse();

            $code = (int) $cdr->getCode();

            if ($code === 0) {

                $observations = count($cdr->getNotes()) > 0 ? implode(", ", $cdr->getNotes()) : 'Sin observaciones';

                return [
                    'success' => true,
                    'message' => 'Factura procesada con éxito.',
                    'code' => $code,
                    'observations' => $observations
                ];
            } elseif ($code >= 2000 && $code <= 3999) {
                return [
                    'success' => false,
                    'message' => 'ESTADO: RECHAZADA',
                    'code' => $code
                ];
            } else {
                /* Esto no debería darse, pero si ocurre, es un CDR inválido que debería tratarse como un error-excepción. */
                /*code: 0100 a 1999 */
                return [
                    'success' => false,
                    'message' => 'Excepción en el CDR recibido.',
                    'code' => $code
                ];
            }
        } catch (\Exception $e) {
            Log::error($e);

            return [
                'success' => false,
                'message' => 'Error al enviar boleta',
                'code' => 500,
            ];
        }
    }
}