<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'apellido_p' => 'required|string|max:255',
            'apellido_m' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telefono' => 'required|string|max:15',
            'password' => [
                'required',
                'string',
                'min:12',
                'regex:/[A-Z]/',
                'regex:/[!@#$%^&*(),.?":{}|<>]/',
                'regex:/^(?!.*(\d)\1{2}).*$/',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'apellido_p' => $request->apellido_p,
            'apellido_m' => $request->apellido_m,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Usuario registrado con éxito', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }
    
        $user = Auth::user();
    
        // Eliminar todos los tokens previos del usuario
        $user->tokens()->delete();
    
        // Crear un nuevo token
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->input('token');
        $accessToken = PersonalAccessToken::findToken($token);

        if ($accessToken) {
            $accessToken->delete();
        }

        return response()->json(['message' => 'Sesión cerrada con éxito']);
    }

    public function update(Request $request)
    {
        $user = User::find($request->input('id'));

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->update($request->all());
        return response()->json(['message' => 'Usuario actualizado con éxito', 'user' => $user]);
    }

    public function delete(Request $request)
    {
        $user = User::find($request->input('id'));

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'Usuario eliminado con éxito']);
    }
}
