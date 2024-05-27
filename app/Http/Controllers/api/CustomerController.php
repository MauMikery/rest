<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\LoginRequest;
use App\Http\Requests\Customers\StoreRequestCustomer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Customer's, return token.
     */
    public function login(LoginRequest $request){
        //Obtenemos los datos validados del formulario login
        $validated = $request->validated();
        
        //Verificamos si podemos autenticar al usuario 
        if(Auth::attempt($validated)){
            $user = $request->user();
            $token = $user->createToken('auth_token')->plainTextToken; 

            $data = [
                "customer" => $user, 
                "access_token" => $token,
                "token_type" => 'Bearer',
                "status" => 200
            ]; 

            return response()->json($data, 200); 
        } else {
            $data = [
                "message" => "Credenciales invalidas",
                "status" => 401
            ]; 

            return response()->json($data, 401); 
        } 
    }
    
    /**
     * Store a newly created customer in storage.
     */
    public function register(StoreRequestCustomer $request)
    {
        $validated = $request->validated(); 

        //Creamos un customer con el modelo y los datos del formulario
        $user = User::create($validated);

        //Si no se pudo crear el customer: 
        if(!$user){
            $data = [
                'message' => 'Error al crear customer',
                'status' => 500
            ]; 

            return response()->json($data, 500); 
        }
        
        //Obtenemos el token 
        $token = $user->createToken('auth_token')->plainTextToken; 

        //Si todo salio bien se retorna el customer y el token
        $data = [
            'customer' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer', 
            'status' => 200
        ]; 

        return response()->json($data, 200); 
    }

     /**
     * Logout user, delete token.
     */
    public function logout(Request $request){
        $request->user()->tokens()->delete(); 

        $data = [
            "message" => "Sesión cerrada correctamente", 
            "status" => 200
        ]; 

        return response()->json($data, 200); 
    }
    
}
