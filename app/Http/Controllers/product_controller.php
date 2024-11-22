<?php

namespace App\Http\Controllers;

use App\Http\Resources\product\product_resource;
use App\Http\Resources\product\show_product_resource;
use App\Models\product;
use App\Rules\price_decimal;
use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class product_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        try {

            $query = Product::query();

            // Agregar búsqueda si se envía un parámetro
            if ($request->has('search')) {
                $query->where('product_name', 'like', '%' . $request->get('search') . '%');
            }
        
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

            // return response()->json([
            //     'error' =>  "Vuelva a registrar",
            //     'success' => false,
            //     'message' => 'Error al crear el producto',
            //     'code' => 400,
            // ], 400);

            // $products = product::with('category', "model")->get();
            // return product_resource::collection($products);
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
                'product_name' => 'required|string|max:255|unique:products,product_name',
                'product_purchase' => ["numeric", "min:0", "required", new price_decimal],
                'product_sales' => ["numeric", "min:0", "required", new price_decimal], //10.00
                'product_stock' => 'required|numeric|min:1'
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
            $validaterData["model_id"] = encryptor::decrypt($request->input("model_id"));
            $validaterData["created_by"] = $userId; //get authenticated user id from token


            //parse string
            $validaterData["product_profit"] = $validaterData["product_sales"] - $validaterData["product_purchase"];

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
                'data' => product_resource::make($product),
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

            return response()->json([
                'error' =>   null,
                'success' => true,
                'message' => 'Producto mostrado exitosamente',
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
