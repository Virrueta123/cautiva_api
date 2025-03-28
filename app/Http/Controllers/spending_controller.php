<?php

namespace App\Http\Controllers;

use App\Http\Resources\spending\spending_index_resource;
use App\Http\Resources\spending\spending_show_resource;
use App\Models\box;
use App\Models\dt_sales_spendings;
use App\Models\payment;
use App\Models\spending;
use App\Rules\price_decimal;
use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class spending_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(  Request $request )
    {
         try {

            $query = spending::query();

            // Agregar búsqueda si se envía un parámetro
            if ($request->has('search')) {
                $query->where('description', 'like', '%' . $request->get('search') . '%');
            }
            $query->orderBy('created_at', 'DESC');

            // Paginación
            $perPage = $request->get('per_page', 10); // Número de elementos por página (opcional)
            $data = $query->paginate($perPage);

            return response()->json([
                "data" => [
                    "last_page" => $data->lastPage(),
                    "per_page" => $perPage,
                    "current_page" => $data->currentPage(),
                    "total" => $data->total(),
                    "data" => spending_index_resource::collection($data->items()),

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
            //comprobar si hay caja abierta   
            $box = box::where("status", "A")->first();
            if ($box == null) {
                return response()->json([
                    'error' =>  "No ninguna caja abierta",
                    'success' => false,
                    'message' => 'Caja',
                    'code' => 400,
                ], 400);
            }

            //comprobar si hay pagos asociados y si el arraya esta vacio

            $paymentModel = $request->input('payment_model');

            if (!$paymentModel) {
                return response()->json([
                    'error' => null,
                    'success' => true,
                    'message' => 'No hay pagos asociados',
                    'code' => 400,
                ], 400);
            }
            $userId = Auth::id();

            $validator = Validator::make($request->all(), [
                'amount' => ["numeric", "min:1", "required", new price_decimal],
                'description' => 'required|string',
                'spending_type_id' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' =>   implode(' | ', $validator->errors()->all()),
                    'success' => false,
                    'message' => 'Error de validación',
                    'code' => 400,
                ], 400);
            }

            $validaterData = $validator->validated();

            $validaterData["spending_type_id"] = encryptor::decrypt($request->input("spending_type_id"));
            $validaterData["created_by"] = $userId;
            $validaterData["created_at"] = now();
            $validaterData["updated_at"] = now(); 
 
 
            $spending_id = Spending::insertGetId($validaterData);


            if ($spending_id) {

                return $this->insertPayment($request->input('payment_model'), $spending_id, $box->box_id, $userId);
            } else {
                return response()->json([
                    'error' =>   null,
                    'success' => false,
                    'message' => 'Error al crear el gasto',
                    'code' => 500,
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false,
                'message' => 'Hubo un error al obtener los productos',
                'code' => 500,
            ], 500);
        }
    }


    // to insert payments of expenses
    public function insertPayment($payment_model, $spending_id, $box_id, $userId)
    {
        try {
            //poner los pagos correspondientes
            $create_payments = [];
            foreach ($payment_model as $payment) {


                $payments = new payment();
                $payments->account_id = encryptor::decrypt($payment["account"]["identifier"]);
                $payments->amount = $payment['amount'];
                $payments->type_payment = "GASTO";
                $payments->created_by = $userId;
                $payments->box_id = $box_id;
                $payments->created_at = now();
                $payments->updated_at = now();
                $payments->save(); 

                array_push(
                    $create_payments,
                    array(
                        'spending_id' => $spending_id,
                        'payment_id' => $payments->payment_id,
                        "created_at" => now(),
                        "updated_at" => now(),
                    )
                );
            }
            dt_sales_spendings::insert($create_payments);
            return response()->json([
                'error' =>   null,
                'success' => true,
                'message' => 'Registro creado exitosamente',
                'code' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false,
                'message' => 'Error al insetar los pagos',
                'code' => 500,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $identifier)
    {
        // show a single spending by identifier
        try {
            $spending = spending::find(encryptor::decrypt($identifier));
            
            if ($spending) {
                return response()->json([
                    'error' => null,
                    'success' => true,
                    'message' => 'Los datos cargaron correctamente',
                    'code' => 200,
                    'data' => new spending_show_resource($spending),
                ], 200);
            } else {
                return response()->json([
                    'error' => null,
                    'success' => false,
                    'message' => 'Spending not found',
                    'code' => 404,
                ], 404);
            }
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
