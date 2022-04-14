<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json([
            "status" => 1,
            "msg" => "!Registro de usuario exitoso¡"
        ]);

    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where("email", "=", "$request->email")->first();

        if( isset($user->id) ){     //verifica si user->id esta definido
            if(Hash::check($request->password, $user->password)){
                // si la password esta correcta creamos el token
                $token = $user->createToken("auth_token")->plainTextToken;

                // si todo esta bien retornamos una respuesta
                return response()->json([
                    "status" => 1,
                    "msg" => "Usuario logeado exitosamente",
                    "access_token" => $token
                ]);
            } else {
                return response()->json([
                    "status" => 0,
                    "msg" => "La password es incorrecta"
                ], 404);
            }
        } else{
            return response()->json([
                "status" => 0,
                "msg" => "Usuario no registrado"
            ], 404);
        }      
    }

    

    //  Funciones protegidas con auth:sanctum y requieren un token

    public function userProfile(){
        return response()->json([
            "status" => 0,
            "msg" => "Acerca del perfil de usuario",
            "data" => auth()->user()
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            "status" => 1,
            "msg" => "Cierre de Sesión"
        ]);

    }

}
