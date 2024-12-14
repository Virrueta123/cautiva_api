<?php

namespace App\Http\Controllers;

use App\Http\Resources\client\client_select_resource;
use App\Models\client;
use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class client_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = client::query();

            // Agregar búsqueda si se envía un parámetro
            if ($request->has('search')) {
                $searchTerm = '%' . $request->get('search') . '%';
                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'like', $searchTerm)
                        ->orWhere('lastname', 'like', $searchTerm)
                        ->orWhere('dni', 'like', $searchTerm);
                });
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
                    "data" => client_select_resource::collection($data->items()),

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
                'name' => 'required|unique:clients,name',
                'lastname' => 'required|unique:clients,lastname',
                'dni' => 'required|string|max:8|unique:clients,dni',
                'address' => "required|string",
                'ruc' => "required|string|max:8|unique:clients,ruc",
                'phone' => 'required|numeric|min:1',
                'bussiness_name' => 'required|string|unique:clients,bussiness_name',
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
            $validaterData["created_by"] = $userId; //get authenticated user id from token

            
            $client = client::create($request->all());
            
            if(!$client){
                return response()->json([
                    'error' =>  "Hubo un error al crear el cliente",
                    'success' => false,
                    'message' => 'Hubo un error al crear el cliente',
                    'code' => 400,
                ], 400);
            }
            return response()->json([
                'error' =>   null,
                'success' => true,
                'message' => 'Cliente creado exitosamente',
                'code' => 200,
                'data' => client_select_resource::collection($client),
            ], 200);
            
        } catch (\Throwable $th) {
            $code = 401;
            return response()->json([
                'error' => $th->getMessage(),
                'success' => false,
                'message' => 'Error al crear el cliente',
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
        try {
            $userId = Auth::id();
    
            // Buscar el cliente por ID
            $client = client::find(encryptor::decrypt($id));
            if (!$client) {
                return response()->json([
                    'error' => 'Cliente no encontrado',
                    'success' => false,
                    'message' => 'No se pudo encontrar el cliente con el ID proporcionado',
                    'code' => 404,
                ], 404);
            }
    
            // Validar los datos
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:clients,name,' . $client->client_id . ',client_id',
                'lastname' => 'required|unique:clients,lastname,' . $client->client_id . ',client_id',
                'dni' => 'required|string|max:8|unique:clients,dni,' . $client->client_id . ',client_id',
                'address' => "required|string",
                'ruc' => "string|max:11|unique:clients,ruc," . $client->client_id . ',client_id',
                'phone' => 'required|numeric|min:1',
                'bussiness_name' => 'string|unique:clients,bussiness_name,' . $client->client_id . ',client_id',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'error' => implode(' | ', $validator->errors()->all()),
                    'success' => false,
                    'message' => 'Error al validar los datos del cliente',
                    'code' => 400,
                ], 400);
            }
    
            // Obtener datos validados
            $validatedData = $validator->validated();
            $validatedData["updated_by"] = $userId; // Agregar el ID del usuario que realiza la edición
    
            // Actualizar cliente
            $client->update($validatedData);
    
            return response()->json([
                'error' => null,
                'success' => true,
                'message' => 'Cliente actualizado exitosamente',
                'code' => 200,
                'data' => $client,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'success' => false,
                'message' => 'Error al actualizar el cliente',
                'code' => 500,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
