<?php

namespace App\Http\Controllers;

use App\Http\Resources\product\product_resource;
use App\Http\Resources\product\show_product_resource;
use App\Models\dt_sales;
use App\Models\product;
use App\Rules\price_decimal;
use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class product_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $query = Product::query();

            // Agregar búsqueda si se envía un parámetro
            if ($request->has('search')) {
                $query->where('product_name', 'like', '%' . $request->get('search') . '%');
            }
            $query->orderBy('product_id', 'DESC');

            // Paginación
            $perPage = $request->get('per_page', 10); // Número de elementos por página (opcional)
            $data = $query->paginate($perPage);

            return response()->json([
                "data" => [
                    "last_page" => $data->lastPage(),
                    "per_page" => $perPage,
                    "current_page" => $data->currentPage(),
                    "total" => $data->total(),
                    "data" => product_resource::collection($data->items()),

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
            $userId = Auth::id();

            $validator = Validator::make($request->all(), [
                'category_id' => 'required',
                'model_id' => 'required',
                'size_id' => 'required',
                'product_name' => 'required|string|max:255|unique:products,product_name',
                'product_purchase' => ["numeric", "min:1", "required", new price_decimal],
                'product_sales' => ["numeric", "min:1", "required", new price_decimal]
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' =>   implode(' | ', $validator->errors()->all()),
                    'success' => false,
                    'message' => 'El producto no se ha creado',
                    'code' => 400,
                ], 400);
            }

            $validaterData = $validator->validated();


            $validaterData["category_id"] = encryptor::decrypt($request->input("category_id"));
            $validaterData["product_stock"] = 1;
            $validaterData["model_id"] = encryptor::decrypt($request->input("model_id"));
            $validaterData["size_id"] = encryptor::decrypt($request->input("size_id"));
            $validaterData["created_by"] =  $userId;
            $validaterData["product_profit"] = $validaterData["product_sales"] - $validaterData["product_purchase"];
            $validaterData["is_hot_sale"] = $request->input("is_hot_sale");

            $product = product::create($validaterData);

            if (!$product) {
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
                'message' => 'Producto creado exitosamente',
                'code' => 200,
                'data' => show_product_resource::make($product),
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, $identifier)
    {
        try {
            $userId = Auth::id();

            $identifier = Encryptor::decrypt($identifier);

            $validator = Validator::make($request->all(), [
                'category_id' => 'required',
                'model_id' => 'required',
                'size_id' => 'required',
                'product_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('products', 'product_name')->ignore($identifier, 'product_id'),
                ],
                'product_purchase' => ["numeric", "min:1", "required", new price_decimal],
                'product_sales' => ["numeric", "min:1", "required", new price_decimal],
                'is_hot_sale' => 'required|in:Y,N'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => implode(' | ', $validator->errors()->all()),
                    'success' => false,
                    'message' => 'El producto no se ha actualizado',
                    'code' => 400,
                ], 400);
            }

            $validatedData = $validator->validated();

            // Buscar el producto por ID
            $product = Product::findOrFail($identifier);

            // Actualizar los valores con los datos validados
            $product->update([
                'category_id' => encryptor::decrypt($request->input("category_id")),
                'model_id' => encryptor::decrypt($request->input("model_id")),
                'size_id' => encryptor::decrypt($request->input("size_id")),
                'product_name' => $validatedData["product_name"],
                'product_purchase' => $validatedData["product_purchase"],
                'product_sales' => $validatedData["product_sales"],
                'product_profit' => $validatedData["product_sales"] - $validatedData["product_purchase"],
                'is_hot_sale' => $validatedData["is_hot_sale"]
            ]);

            return response()->json([
                'error' => null,
                'success' => true,
                'message' => 'Producto actualizado exitosamente',
                'code' => 200,
                'data' => show_product_resource::make($product),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'success' => false,
                'message' => 'Error al actualizar el producto',
                'code' => 500,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $identifier)
    {
        try {
            $product = product::find(Encryptor::decrypt($identifier));

            if (!$product) {
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
                'data' => show_product_resource::make($product),
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $identifier)
    {
        try {

            $sale = ::find(encryptor::decrypt($identifier));

            if (!$sale) {
                return response()->json([
                    'error' =>  "Venta no encontrado",
                    'success' => false,
                    'message' => 'Venta no encontrado',
                    'code' => 404,
                ], 404);
            }

            if ($sale->estado == "D") {
                return response()->json([
                    'error' =>  "Venta ya anulada",
                    'success' => false,
                    'message' => 'Venta ya anulada',
                    'code' => 404,
                ]);
            }

            if ($sale->tipo_documento == "N") {
                $sale->fecha_baja = Carbon::now()->format('Y-m-d H:i:s');
                $sale->motivo_cancelacion = $request->input('motivo');
                $sale->estado = "D";
                $sale->save();

                //eleimnar productos en el dt_sale
                dt_sales::where('sale_id', $sale->sale_id)->delete();

                //eleiminar los payments
                dt_sales_payments::where('sale_id', $sale->sale_id)->each(function ($dtSalePayment) {
                    $dtSalePayment->payment->delete(); // Eliminar cada pago relacionado
                });

                //eleiminar pagos en el dt_sales_payments
                dt_sales_payments::where('sale_id', $sale->sale_id)->delete();

                return response()->json([
                    'error' =>  "Venta anulada exitosamente",
                    'success' => true,
                    'message' => 'Operacion  exitosamente',
                    'code' => 200,
                ], 200);
            }

            $unsubscribeTicket = $this->greenterService->unsubscribeTicket(
                $sale->correlativo,
                $request->input('motivo'),
                $sale->serie
            );

            if ($unsubscribeTicket["success"]) {
                $sale->estado = "D";
                $sale->save();

                return response()->json([
                    'error' =>   null,
                    'success' => true,
                    'message' => 'Venta anulada exitosamente',
                    'code' => 200,
                ], 200);
            } else {
                return response()->json([
                    'error' =>  "Error al anular la venta",
                    'success' => false,
                    'message' => 'Error al anular la venta',
                    'code' => 400,
                ], 400);
            }
        } catch (\Throwable $th) {
            $code = 401;
            return response()->json([
                'error' => $th->getMessage(),
                'success' => false,
                'message' => 'Error al mostrar el venta',
                'code' => $code,
            ], $code);
        }
    }

    /**
     * Show the product by barcode.
     *
     * @return \Illuminate\Http\Response
     */

    public function barcode(string $barcode)
    {
        try {
            $product = Product::where('barcode', $barcode)->first();

            if (!$product) {
                return response()->json([
                    'error' =>  "El codigo no exite en la base de datos",
                    'success' => false,
                    'message' => 'Producto no encontrado',
                    'code' => 404,
                ], 404);
            }

            $sale = dt_sales::where('product_id', $product->product_id)->first();

            if ($sale) {
                return response()->json([
                    'error' =>  "El producto ya se vendio",
                    'success' => false,
                    'message' => 'Producto',
                    'code' => 404,
                ], 404);
            }

            return response()->json([
                'error' =>   null,
                'success' => true,
                'message' => 'Producto mostrado exitosamente',
                'code' => 200,
                'data' => product_resource::make($product),
            ], 200);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            $code = 401;
            return response()->json([
                'error' => "Error al mostrar el producto",
                'success' => false,
                'message' => 'Error al mostrar el producto',
                'code' => $code,
            ], $code);
        }
    }

    public function barcode_print($identifier)
    {
        try {
            $product = Product::find(Encryptor::decrypt($identifier));


            if (!$product) {
                return response()->json([
                    'error' =>  "Error al imprimir",
                    'success' => false,
                    'message' => 'Producto no encontrado',
                    'code' => 404,
                ], 404);
            }


            // URL a la que deseas hacer la solicitud
            // $url = 'https://fowl-sacred-strangely.ngrok-free.app/print_script/public/ipc';
            $url = 'https://fowl-sacred-strangely.ngrok-free.app/print_script/public/ipc';



            // Datos que deseas enviar en la solicitud POST
            $postData = array(
                'barcode' => $product->barcode,
                'price' => $product->product_sales,
                'product_name' => $product->product_name . " " . $product->size->size_name,
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

            return response()->json([
                'error' =>   null,
                'success' => true,
                'message' => 'imprimio con exito el codigo',
                'code' => 200,
                'data' => product_resource::make($product),
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
}
