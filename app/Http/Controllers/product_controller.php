<?php

namespace App\Http\Controllers;

use App\Http\Resources\product_resource;
use App\Models\product;
use App\Rules\price_decimal;
use App\Utils\Encryptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class product_controller extends Controller
{
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
            $validator = Validator::make($request->all(), [
                'category_id' => 'required',
                'model_id' => 'required',
                'product_name' => 'required|string|max:255|unique:products,product_name',
                'product_purchase' => ["numeric","min:0","required",new price_decimal],
                'product_sales' => ["numeric","min:0","required",new price_decimal], //10.00
                'product_stock' => 'required|numeric|min:1' 
            ]);
 
            if ($validator->fails()) {
                return response()->json([
                    'error' =>   implode(' | ', $validator->errors()->all()),
                    'success' => false,
                    'message' => 'Error al intentar autenticar',
                    'code' => 400,
                ], 400);
            }

            $validaterData = $validator->validated();
            
            $validaterData["category_id"] = Encryptor::decrypt($request->input( "category_id" ));
            $validaterData["model_id"] = Encryptor::decrypt($request->input( "model_id" ));
  

            $product = product::create($validaterData);

            if(!$product){
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
    public function show(string $id)
    {
        //
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
}
