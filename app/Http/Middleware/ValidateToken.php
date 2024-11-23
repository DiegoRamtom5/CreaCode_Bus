<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class ValidateToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->input('token'); // Leemos el token desde los parámetros

        if (!$token) {
            return response()->json(['message' => 'Token requerido'], 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        // Asociar el usuario autenticado al token válido
        $request->user = $accessToken->tokenable;

        return $next($request);
    }
}
