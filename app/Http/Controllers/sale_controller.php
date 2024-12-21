<?php

namespace App\Http\Controllers;

use App\Http\Resources\product\show_product_resource;
use App\Http\Resources\sale\sale_index_resource;
use App\Http\Resources\sale\sale_show_resource;
use App\Models\box;
use App\Models\client;
use App\Models\config;
use App\Models\dt_sales;
use App\Models\dt_sales_payments;
use App\Models\payment;
use App\Models\product;
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
    public function index(Request $request)
    {
        try {
            $query = sale::query();

            // Agregar búsqueda si se envía un parámetro
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('setNombre', 'like', '%' . $search . '%')
                        ->orWhere('setRuc', 'like', '%' . $search . '%')
                        ->orWhere('setRazonSocial', 'like', '%' . $search . '%')
                        ->orWhere('setApellido', 'like', '%' . $search . '%')
                        ->orWhere('setDni', 'like', '%' . $search . '%');
                });
            }

            $query->orderBy('sale_id', 'DESC');

            // Paginación
            $perPage = $request->get('per_page', 10); // Número de elementos por página (opcional)
            $data = $query->paginate($perPage);

            return response()->json([
                "data" => [
                    "last_page" => $data->lastPage(),
                    "per_page" => $perPage,
                    "current_page" => $data->currentPage(),
                    "total" => $data->total(),
                    "data" => sale_index_resource::collection($data->items()),

                ],
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
            $datax = $request->all();

            $config = config::where('config_id', 1)->first();

            $userId = Auth::id();

            $box = box::where("status", "A")->first();
            //comprobar si hay caja abierta
            if ($box == null) {
                return response()->json([
                    'error' =>  "No hay caja abierta",
                    'success' => false,
                    'message' => 'Caja',
                    'code' => 400,
                ], 400);
            }

            $box_id = $box->box_id;

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

                // $product = dt_sales::where("product_id", encryptor::decrypt($product["identifier"]));

                // if ($product) {
                //     return response()->json([
                //         'error' =>   "Producto ya se vendio",
                //         'success' => false,
                //         'message' => 'Error al crear la venta',
                //         'code' => 401,
                //         'data' => $sale,
                //     ], 401);
                // }

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

            $total = $request->input('total');

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
                        $sale->estado = "E";
                        $sale->observations = $result["observations"];
                        $sale->message_error = $result["message"];
                        $sale->codigo_error = $result["code"];
                        $sale->save();

                        $create_dt_sale = array_map(function ($sales) use ($sale) {
                            return array_merge($sales, [
                                'sale_id' => $sale->sale_id,
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                        }, $create_dt_sale);

                        $this->insertPayment($request->input('payment_model'), $sale, $box_id, $userId);
                        dt_sales::insert($create_dt_sale);

                        return response()->json([
                            'error' =>   null,
                            'success' => true,
                            'message' => 'Venta cargado exitosamente',
                            'code' => 200,
                            'data' => encryptor::encrypt($sale->sale_id),
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

                        $sale->estado = "E";
                        $sale->observations = $result["observations"];
                        $sale->message_error = $result["message"];
                        $sale->codigo_error = $result["code"];
                        $sale->save();

                        $create_dt_sale = array_map(function ($sales) use ($sale) {
                            return array_merge($sales, [
                                'sale_id' => $sale->sale_id,
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                        }, $create_dt_sale);

                        $this->insertPayment($request->input('payment_model'), $sale, $box_id, $userId);
                        dt_sales::insert($create_dt_sale);

                        return response()->json([
                            'error' =>   null,
                            'success' => true,
                            'message' => 'Venta cargado exitosamente',
                            'code' => 200,
                            'data' =>  encryptor::encrypt($sale->sale_id),
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
                    $sale->estado = "E";

                    if (!$sale->save()) {
                        return response()->json([
                            'error' =>   null,
                            'success' => false,
                            'message' => 'Error al crear la venta',
                            'code' => 200,
                        ]);
                    }

                    $create_dt_sale = array_map(function ($sales) use ($sale) {
                        return array_merge($sales, [
                            'sale_id' => $sale->sale_id,
                            "created_at" => now(),
                            "updated_at" => now(),
                        ]);
                    }, $create_dt_sale);

                    $this->insertPayment($request->input('payment_model'), $sale, $box_id, $userId);
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

    public function insertPayment($payment_model, $sale, $box_id, $userId)
    {
        //poner los pagos correspondientes
        $create_payments = [];
        foreach ($payment_model as $payment) {

            $payments = new payment();
            $payments->account_id = encryptor::decrypt($payment["account"]["identifier"]);
            $payments->amount = $payment['amount'];
            $payments->type_payment = "VENTA";
            $payments->created_by = $userId;
            $payments->box_id = $box_id;
            $payments->created_at = now();
            $payments->updated_at = now();
            $payments->save();

            array_push(
                $create_payments,
                array(
                    'sale_id' => $sale->sale_id,
                    'payment_id' => $payments->payment_id,
                    "created_at" => now(),
                    "updated_at" => now(),
                )
            );
        }

        dt_sales_payments::insert($create_payments);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $identifier)
    {

        try {
            $sale = sale::find(encryptor::decrypt($identifier));

            if (!$sale) {
                return response()->json([
                    'error' =>  "Producto no encontrado",
                    'success' => false,
                    'message' => 'Producto no encontrado',
                    'code' => 404,
                ], 404);
            }

            return response()->json([
                'error' =>   null,
                'success' => true,
                'message' => 'Producto mostrado exitosamente',
                'code' => 200,
                'data' => sale_show_resource::make($sale),
            ], 200);
        } catch (\Throwable $e) {
            $code = 401;
            return response()->json([
                'error' => "Error al mostrar el producto",
                'success' => false,
                'message' => 'Error al mostrar el producto',
                'code' => $code,
            ], $code);
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

                $pdf = Pdf::loadView(
                    'receipt.receipt',
                    [
                        "qrCode" => $qrCode
                    ]
                );

                // Codificar el contenido del PDF en base64
                $pdfContent = base64_encode($pdf->output());

                // Responder con datos JSON incluyendo el PDF
                return response()->json([
                    'error' => null,
                    'success' => true,
                    'message' => 'Venta cargado exitosamente',
                    'code' => 200,
                    'data' => $pdfContent, // PDF codificado
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
