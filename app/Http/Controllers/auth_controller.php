<?php

namespace App\Http\Controllers;

use App\Http\Resources\login_resource;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class auth_controller extends Controller
{
    private $validation_password_and_username;

    public function __construct()
    {
        $this->validation_password_and_username = [
            'username.required' => 'El campo nombre de usuario es requerido para continuar',
            'username.unique' => 'El nombre de usuario debe ser único',
            'username.max' => 'El nombre de usuario no debe superar los 255 caracteres',
            'username.string' => 'El nombre de usuario debe ser una cadena de texto',

            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'La confirmación de la contraseña no coincide',
        ];
    }

    public function register(Request $request)
    {
        $validator_message = [
            // Custom messages...
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no debe superar los 255 caracteres',
            'name.string' => 'El nombre debe puro texto',
        ];

        $validator_message = array_merge($this->validation_password_and_username, $validator_message);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
        ],  $validator_message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 401);
        }

        $user = user::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 202);
    }

    public function login(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255',
                'password' => 'required|min:8',
            ], $this->validation_password_and_username);

            if ($validator->fails()) {
                return response()->json([
                    'error' =>   implode(' | ', $validator->errors()->all()),
                    'success' => false,
                    'message' => 'Error al intentar autenticar',
                    'code' => 400,
                ], 400);
            }

            if (!Auth::attempt($request->only('username', 'password'))) {
                throw ValidationException::withMessages([
                    'username' => 'las credenciales son incorrectas, vuelva a intentarlo',
                ]);
            }

            $user = user::where('username', $request->username)->first();
            $token = $user->createToken('auth_token')->plainTextToken;
 
            return response()->json([ 
                'token_type' => 'Bearer',
                'success' => true,
                'message' => 'Autenticado correctamente',
                'code' => 200,
                "data" => [
                    'access_token' => $token,
                    "user" =>  login_resource::make($user),
                ],
            ],200);


        } catch (\Throwable $th) {
            //throw $th;
            $code = 401;
            return response()->json([
                'error' => $th->getMessage(),
                'success' => false,
                'message' => 'Error al intentar autentificar',
                'code' => $code,
            ], $code);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        $user = user::all();
        return response()->json([ 
            'success' => true,
            'message' => 'Error al intentar autenticar',
            'code' => 200,
            "data" => login_resource::collection($user),
        ], 200);
    }
}
