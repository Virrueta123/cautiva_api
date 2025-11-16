<?php

namespace App\Http\Controllers;

use App\Http\Resources\box\box_details_sale_resource;
use App\Http\Resources\box\box_index_resource;
use App\Http\Resources\box\box_show_resource;
use App\Models\account;
use App\Models\box;
use App\Models\payment;
use App\Utils\encryptor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class box_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $query = Box::query();

            // Agregar búsqueda si se envía un parámetro
            if ($request->has('search')) {
                $query->where('reference', 'like', '%' . $request->get('search') . '%');
            }
            $query->orderBy('box_id', 'DESC');

            // Paginación
            $perPage = $request->get('per_page', 10); // Número de elementos por página (opcional)
            $data = $query->paginate($perPage);

            return response()->json([
                "data" => [
                    "last_page" => $data->lastPage(),
                    "per_page" => $perPage,
                    "current_page" => $data->currentPage(),
                    "total" => $data->total(),
                    "data" => box_index_resource::collection($data->items()),

                ],
                'success' => true,
                'code' => 200,
            ], 200);


            if (!$box) {
                return response()->json([
                    'error' =>  "Error al mostrar caja",
                    'success' => false,
                    'message' => 'Error al mostrar',
                    'code' => 400,
                ], 400);
            }

            return response()->json([
                "message" => "Cajas mostratadas exitosamente",
                'success' => true,
                'code' => 200,
                'data' => box_index_resource::collection($box),
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
                'reference' => 'required',
                'initial_balance' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' =>   implode(' | ', $validator->errors()->all()),
                    'success' => false,
                    'message' => 'Corregir estos detalles',
                    'code' => 400,
                ], 400);
            }

            //mostar un error si ya hay una caja abierta
            if (box::where("status", "A")->count() > 0) {
                return response()->json([
                    'error' =>  "Ya esxite una caja abierta",
                    'success' => false,
                    'message' => 'Error al crear esta caja',
                    'code' => 400,
                ], 400);
            }


            $validaterData = $validator->validated();
            $validaterData["created_by"] = $userId; //get authenticated user id from token
            $validaterData["status"] = "A";
            $validaterData["opening_date"] = Carbon::now()->format('Y-m-d H:i:s');


            $box = box::create($validaterData);

            if (!$box) {
                return response()->json([
                    'error' =>  "Error al crear la caja",
                    'success' => false,
                    'message' => 'Error en la caja',
                    'code' => 400,
                ], 400);
            }

            return response()->json([
                "message" => "Caja creado exitosamente",
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
     * Display the specified resource.
     */

    public function box_sale($identifier)
    {
        try {
            $box = box::find(encryptor::decrypt($identifier));

            $account = account::all();

            $payment_account_general = [];

            foreach ($account as $acc) {
                $payment = payment::where("account_id", $acc->account_id)->where("box_id", $box->box_id)->where("type_payment", "VENTA")->get()->sum('amount');
                array_push($payment_account_general, array("account_name" => $acc->account_name, "amount" =>  strval($payment)));
            } 
  
            if (!$box) {
                return response()->json([
                    'error' =>  "Error al obtener la caja",
                    'success' => false,
                    'message' => 'Error en la caja',
                    'code' => 400,
                ], 400);
            }

            return response()->json([
                "message" => "Caja mostrada exitosamente",
                'success' => true,
                'data' => new box_details_sale_resource($box, $payment_account_general),
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

   public function box_sending($identifier)
    {
        try {
            $box = box::find(encryptor::decrypt($identifier));

            $account = account::all();

            $payment_account_general = [];

            foreach ($account as $acc) {
                $payment = payment::where("account_id", $acc->account_id)->where("box_id", $box->box_id)->where("type_payment", "GASTO")->get()->sum('amount');
                array_push($payment_account_general, array("account_name" => $acc->account_name, "amount" =>  $payment));
            }
  
            if (!$box) {
                return response()->json([
                    'error' =>  "Error al obtener la caja",
                    'success' => false,
                    'message' => 'Error en la caja',
                    'code' => 400,
                ], 400);
            }

            return response()->json([
                "message" => "Caja mostrada exitosamente",
                'success' => true,
                'data' => new box_details_sale_resource($box, $payment_account_general),
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $box = box::find(encryptor::decrypt($id));

            if (!$box) {
                return response()->json([
                    'error' =>  "Error al obtener la caja",
                    'success' => false,
                    'message' => 'Error en la caja',
                    'code' => 400,
                ], 400);
            }

            return response()->json([
                "message" => "Caja mostrada exitosamente",
                'success' => true,
                'data' => new box_show_resource($box),
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $identifier)
    {
        try {
            if ($identifier) {
                $box = box::find(encryptor::decrypt($identifier));

                if (!$box) {
                    return response()->json([
                        'error' =>  "Error al eliminar la caja",
                        'success' => false,
                        'message' => 'Esta caja no existe',
                        'code' => 400,
                    ], 400);
                }

                if ($box->spending_amount->count() > 0 || $box->sales_amount->count() > 0) {
                    return response()->json([
                        'error' =>  "La caja tiene ingresos o gastos, no puede eliminarse",
                        'success' => false,
                        'message' => 'Error al eliminar la caja',
                        'code' => 400,
                    ], 400);
                }

                $box->delete();

                return response()->json([
                    "message" => "Caja cerrada exitosamente",
                    'success' => true,
                    'code' => 200,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false,
                'message' => 'Hubo un error al cerrar esta caja',
                'code' => 500,
            ], 500);
        }
    }


    public function close(string $identifier)
    {
        try {
            if ($identifier) {
                $box = box::find(encryptor::decrypt($identifier));

                if (!$box) {
                    return response()->json([
                        'error' =>  "Error al crear la caja",
                        'success' => false,
                        'message' => 'Error en la caja',
                        'code' => 400,
                    ], 400);
                }


                if ($box->status == "C") {
                    return response()->json([
                        'error' =>  "La caja ya se encuentra cerrada {$box->status}",
                        'success' => false,
                        'message' => 'Error en la caja',
                        'code' => 400,
                    ], 400);
                }

                // si la caja no tiene ningun ingreso o gasto no se puede cerrar
                if ($box->spending_amount->count() == 0 && $box->sales_amount->count() == 0) {
                    return response()->json([
                        'error' =>  "La caja no tiene ingresos ni gastos",
                        'success' => false,
                        'message' => 'Error al cerrar caja',
                        'code' => 400,
                    ], 400);
                }

                $box->status = "C";
                $box->closing_date = Carbon::now();
                $box->final_balance = ($box->sales_amount->sum('amount') - $box->spending_amount->sum('amount')) + $box->initial_balance;
                $box->save();

                return response()->json([
                    "message" => "Caja cerrada exitosamente",
                    'success' => true,
                    'code' => 200,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'success' => false,
                'message' => 'Hubo un error al cerrar esta caja',
                'code' => 500,
            ], 500);
        }
    }
}
