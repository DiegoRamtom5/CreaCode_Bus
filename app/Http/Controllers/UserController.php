<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;

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

         // Dentro del método register
        event(new Registered($user));  // Esto disparará la notificación de verificación

        return response()->json(['message' => 'Usuario registrado con éxito', 'user' => $user], 201);
    }

    public function verifyEmail($id, $hash)
    {
        // Buscar al usuario por su ID
        $user = User::findOrFail($id);

        // Verificar el hash
        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            // Si el hash no coincide, puedes lanzar un error o redirigir
            return response()->json(['message' => 'Invalid verification link.'], 400);
        }

        // Verificar y marcar el correo como verificado
        if ($user->hasVerifiedEmail()) {
            return redirect('login.html');  // Redirige a login.html si ya está verificado
        }

        // Marcar como verificado
        $user->markEmailAsVerified();

        // Emitir el evento de verificación
        event(new Verified($user));

        // Redirigir a login.html después de la verificación
        return redirect('login.html');  // Aquí rediriges a login.html
    }


    public function registerU(Request $request)
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
            'rol' => 'required|integer',
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
            'rol' => $request->rol,
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
            'rol' => $user->rol,
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
    public function verDetallesUsuario(Request $request)
{
    $token = $request->input('token');
    if (!$this->validateToken($token)) {
        return response()->json(['message' => 'Token inválido'], 401);
    }

    $id = $request->input('id'); // ID del usuario a consultar
    if (!$id) {
        return response()->json(['message' => 'El ID del usuario es requerido'], 400);
    }

    $usuario = User::find($id); // Busca al usuario por ID
    if (!$usuario) {
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    return response()->json(['usuario' => $usuario], 200);
}
public function listaUsuarios(Request $request)
{
    $token = $request->input('token');
    Log::info("Token recibido: " . $token);
    
    if (!$this->validateToken($token)) {
        Log::error("Token inválido");
        return response()->json(['message' => 'Token inválido'], 401);
    }

    try {
        $usuarios = User::all();
    } catch (\Exception $e) {
        Log::error("Error al obtener usuarios: " . $e->getMessage());
        return response()->json(['message' => 'Error interno'], 500);
    }

    return response()->json(['usuarios' => $usuarios], 200);
}

private function validateToken($token)
{
    $accessToken = PersonalAccessToken::findToken($token);
    return $accessToken && $accessToken->tokenable_type === 'App\Models\User';
}

}
