<?php

namespace App\Http\Controllers;

use App\Models\Autobus;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AutobusController extends Controller
{
    public function create(Request $request)
    {
        $token = $request->input('token');
        if (!$this->validateToken($token)) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        $request->validate([
            'numero_autobus' => 'required|string|max:255',
            'linea' => 'required|string|max:255',
            'capacidad' => 'required|integer|min:1',
            'servicios' => 'nullable|string',
            'num_incidencia' => 'required|integer|min:1',
        ]);

        $autobus = Autobus::create([
            'numero_autobus' => $request->numero_autobus,
            'linea' => $request->linea,
            'capacidad' => $request->capacidad,
            'servicios' => $request->servicios,
            'num_incidencia' => 0,
        ]);

        return response()->json(['message' => 'Autobús creado con éxito', 'autobus' => $autobus], 201);
    }

    public function update(Request $request)
    {
        $token = $request->input('token');
        if (!$this->validateToken($token)) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        $autobus = Autobus::find($request->input('id'));
        if (!$autobus) {
            return response()->json(['message' => 'Autobús no encontrado'], 404);
        }

        $autobus->update($request->all());

        return response()->json(['message' => 'Autobús actualizado con éxito', 'autobus' => $autobus]);
    }

    public function delete(Request $request)
    {
        $token = $request->input('token');
        if (!$this->validateToken($token)) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        $autobus = Autobus::find($request->input('id'));
        if (!$autobus) {
            return response()->json(['message' => 'Autobús no encontrado'], 404);
        }

        $autobus->delete();

        return response()->json(['message' => 'Autobús eliminado con éxito']);
    }

    public function view(Request $request)
    {
        $token = $request->input('token');
        if (!$this->validateToken($token)) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        $autobus = Autobus::all();

        return response()->json(['autobuses' => $autobus]);
    }

    private function validateToken($token)
    {
        $accessToken = PersonalAccessToken::findToken($token);
        return $accessToken && $accessToken->tokenable_type === 'App\Models\User';
    }
}
