<?php

namespace App\Http\Controllers;

use App\Models\box;
use App\Models\client;
use App\Models\config;
use App\Models\dt_sales;
use App\Models\sale;
use App\Services\greenter_service;
use App\Utils\correlativo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Utils\encryptor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class sale_controller extends Controller
{
    protected $greenterService;

    public function __construct(greenter_service $greenterService)
    {
        $this->greenterService = $greenterService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $datax = $request->all();

            $config = config::where('config_id', 1)->first();

            $userId = Auth::id();

            $box_id = box::where("status", "A")->first()->box_id;

            //si es cliente varios o no
            $isClientOther = $datax["isClientOther"];

            //tipo de comprobante
            $tipo_documento = $datax["type_of_receipt"];

            //totales
            $total_sale = number_format($datax["total"], 2);
            $subtotal_sale = number_format($datax["subtotal"], 2);


            //descuentos
            $amount_discount = $datax["amount_discount"];
            $total_descuento = $datax["total_descuento"];



            if ($isClientOther) {
                $client = client::find(1);
            } else {
                $client = client::find(encryptor::decrypt($datax["client_id"]));
            }

            $sale = new sale();
            $sale->client_id = $client->client_id;
            $sale->box_id = $box_id;
            $sale->created_by = $userId;
            $sale->tipo_documento = $tipo_documento;
            $sale->serie = $this->getSerie($tipo_documento);
            $sale->correlativo = $this->generateCorrelativo($tipo_documento);
            $sale->setRuc = $tipo_documento == "F" ? $client->ruc : null;
            $sale->setRazonSocial = $tipo_documento == "F" ? $client->bussiness_name : null;
            $sale->setMtoOperGravadas = 0;
            $sale->setMtoOperExoneradas = $total_sale;
            $sale->setMtoIGV = 0;
            $sale->setTotalImpuestos = 0;
            $sale->setValorVenta = $total_sale;
            $sale->setSubTotal = $total_sale;
            $sale->setMtoImpVenta = $total_sale;
            $sale->setMtoOperExoneradas = $total_sale;
            $sale->setMtoOperInafectas = 0;
            $sale->setMtoOtrosCargos = 0;
            $sale->setDni = $client->dni;
            $sale->setNombre = $client->name;
            $sale->setApellido = $client->lastname;
            $sale->descuento = $total_descuento;
            $sale->subtotal = $subtotal_sale;
            $sale->total = $total_sale;

            //productos
            $create_dt_sale = [];
            foreach ($datax["products"] as $product) {
                $producto_descontado = $product["product_sales"] - $amount_discount;
                array_push(
                    $create_dt_sale,
                    array(
                        "product_id" => encryptor::decrypt($product["identifier"]),
                        "created_by" => $userId,
                        "Cantidad" => 1,
                        "PorcentajeIgv" => 0,
                        "Igv" => 0,
                        "TotalImpuestos" => 0,
                        "MtoValorVenta" => $producto_descontado,
                        "MtoValorUnitario" => $producto_descontado,
                        "MtoPrecioUnitario" => $producto_descontado,
                        "CodProducto" => $product["barcode"],
                        "Unidad" => "unidad",
                        "Descripcion" => $product["product_name"] . " " . $product["category"]["category_name"] . " " . $product["size"]["size_name"],
                        "TipAfeIgv" => $producto_descontado,
                        "BaseIgv" => 0,
                        "discount" => $amount_discount,
                    )
                );
            }

            //emitir a la sunat 
            $client_repeit = $this->greenterService->createClientInvoice(
                $tipo_documento,
                $tipo_documento == "F" ? $client->ruc : $client->dni,
                $tipo_documento == "F" ? $client->bussiness_name : $client->name . " " . $client->lastname
            );

            $items = $request->input('items');
            $total = $request->input('total');

            if ($tipo_documento == "F") {
            }

            switch ($tipo_documento) {
                case "F":
                    $invoice = $this->greenterService->createInvoice(
                        $client_repeit,
                        $create_dt_sale,
                        $sale->serie,
                        $sale->correlativo,
                        $total,
                    );
                    $result = $this->greenterService->sendInvoice($invoice);

                    if ($result["success"]) {
                        return response()->json([
                            'error' =>   null,
                            'success' => true,
                            'message' => 'Venta cargado exitosamente',
                            'code' => 200,
                            'data' => $sale,
                        ], 200);
                    } else {
                        return response()->json([
                            'error' =>   null,
                            'success' => false,
                            'message' => 'Error al crear la venta',
                            'code' => 200,
                            'data' => $sale,
                        ], 500);
                    }
                    break;
                case "B":
                    $invoice = $this->greenterService->createTicket(
                        $client_repeit,
                        $create_dt_sale,
                        $sale->serie,
                        $sale->correlativo,
                        $total,
                    );
                    $result = $this->greenterService->sendTicket($invoice);

                    if ($result["success"]) {

                        $sale->estado = "A";
                        $sale->observations = $result["observations"];
                        $sale->message_error = $result["message"];
                        $sale->codigo_error = $result["code"];
                        $sale->save();

                        $create_dt_sale = array_map(function ($sales) use ($sale) {
                            return array_merge($sales, ['sale_id' => $sale->sale_id]);
                        }, $create_dt_sale);

                        dt_sales::insert($create_dt_sale);

                        return response()->json([
                            'error' =>   null,
                            'success' => true,
                            'message' => 'Venta cargado exitosamente',
                            'code' => 200,
                            'data' => $result,
                        ], 200);
                    } else {
                        return response()->json([
                            'error' =>   null,
                            'success' => false,
                            'message' => 'Error al crear la venta',
                            'code' => 200,
                            'data' =>  "",
                        ], 500);
                    }
                    break;
                case "N":
                    $sale->estado = "A"; 
                  
                    if(!$sale->save()){ 
                        return response()->json([
                            'error' =>   null,
                            'success' => false,
                            'message' => 'Error al crear la venta',
                            'code' => 200, 
                        ]);
                    }

                    $create_dt_sale = array_map(function ($sales) use ($sale) {
                        return array_merge($sales, ['sale_id' => $sale->sale_id]);
                    }, $create_dt_sale);

                    dt_sales::insert($create_dt_sale);
 
                        return response()->json([
                            'error' =>   null,
                            'success' => true,
                            'message' => 'Venta generada correctamente exitosamente',
                            'code' => 200,
                            'data' =>  encryptor::encrypt($sale->sale_id),
                        ], 200);
              
                    break;
            }
 
         
        
        } catch (\Throwable $th) {
            $code = 401;
            return response()->json([
                'error' => $th->getMessage(),
                'success' => false,
                'message' => 'error al crear el comprobante',
                'code' => $code,
            ], $code);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $identifier)
    {
        $sales = Sale::find(encryptor::decrypt($identifier));
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

    //adicionales
    public function share($identifier)
    {
        try {
            // $sale = sale::find($id);

            if (true) {
                $qrCode = QrCode::format('svg')->size(200)->generate("www.cautivamodayestiloamericano.shop");
                $ticket = Blade::compileString(view(
                    'receipt.ticket',
                    []
                )->render());
                $pdf = Pdf::loadView(
                    'receipt.receipt',
                    [
                        "receipt" => $ticket,
                        "qrCode" => $qrCode
                    ]
                );
                return $pdf->stream('invoice.pdf');
                return response()->json([
                    'error' =>   null,
                    'success' => true,
                    'message' => 'Venta cargado exitosamente',
                    'code' => 200,
                    'data' => "",
                ], 200);
            }

            return response()->json([
                'error' =>  "no hay registros",
                'success' => false,
                'message' => 'Error al cargar venta',
                'code' => 400,
            ], 400);
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

    public function generateCorrelativo($type_of_receipt)
    {

        $correlativo_comprobante = sale::where('tipo_documento', $type_of_receipt)->max('correlativo');

        if (is_null($correlativo_comprobante)) {
            $correlativo_comprobante = 1;
        } else {
            $correlativo_comprobante++;
        }

        return  correlativo::formatoCorrelativo($correlativo_comprobante);
    }

    public function getSerie($type_of_receipt)
    {
        $config = config::find(1);


        switch ($type_of_receipt) {
            case 'F':
                return $config->series_invoice;
                break;
            case 'B':
                return $config->series_ticket;
                break;
            case 'N':
                return $config->series_note;
                break;
        }
    }
}
