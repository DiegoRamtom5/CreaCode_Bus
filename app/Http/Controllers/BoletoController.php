<?php

namespace App\Http\Controllers;

use App\Models\Boleto;
use App\Models\Corrida;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class BoletoController extends Controller
{
    // Compra de boletos
    public function comprarBoleto(Request $request)
    {
        $token = $request->input('token');
        $user = $this->getUserByToken($token);
    
        if (!$user) {
            return response()->json(['message' => 'Token inválido'], 401);
        }
    
        $validated = $request->validate([
            'id_corrida' => 'required|exists:corrida,id',
            'asientos' => 'required|array|min:1',
            'asientos.*' => 'integer|min:1',
        ]);
    
        $corrida = Corrida::find($validated['id_corrida']);
    
        // Validar disponibilidad de asientos
        $asientosDisponibles = collect($corrida->asientos);
        foreach ($validated['asientos'] as $asiento) {
            $asientoEncontrado = $asientosDisponibles->firstWhere('numero', $asiento);
            if (!$asientoEncontrado || $asientoEncontrado['estado'] !== 'disponible') {
                return response()->json(['message' => "Asiento {$asiento} no disponible"], 400);
            }
        }
    
        // Marcar los asientos como ocupados
        $corrida->asientos = $asientosDisponibles->map(function ($asiento) use ($validated) {
            if (in_array($asiento['numero'], $validated['asientos'])) {
                $asiento['estado'] = 'ocupado';
            }
            return $asiento;
        })->toArray();
        $corrida->save();
    
        // Crear boleto
        $boleto = Boleto::create([
            'num_boleto' => uniqid('BOL'),
            'id_usuario' => $user->id,
            'id_corrida' => $validated['id_corrida'],
            'num_asientos' => count($validated['asientos']),
            'fecha_compra' => now(),
            'monto' => $corrida->precio * count($validated['asientos']),
            'descuento' => 0,
            'id_pago' => 1, // Simulado
            'estado' => 1,  // Activo
        ]);
    
        return response()->json(['message' => 'Boleto comprado con éxito', 'boleto' => $boleto]);
    }
    
    

 // Cancelar boletos
    public function cancelarBoleto(Request $request)
    {
        $token = $request->input('token');
        $user = $this->getUserByToken($token);

        if (!$user) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        $validated = $request->validate([
            'id_boleto' => 'required|exists:boleto,id',
        ]);

        $boleto = Boleto::find($validated['id_boleto']);

        if ($boleto->id_usuario !== $user->id) {
            return response()->json(['message' => 'No autorizado para cancelar este boleto'], 403);
        }

        $boleto->estado = 0; // Cancelado
        $boleto->save();

        return response()->json(['message' => 'Boleto cancelado con éxito']);
    }
    // Visualizar boletos
    public function visualizarBoletos(Request $request)
    {
        $token = $request->input('token');
        $user = $this->getUserByToken($token);

        if (!$user) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        $boletos = Boleto::where('id_usuario', $user->id)->get();

        return response()->json(['message' => 'Boletos obtenidos con éxito', 'boletos' => $boletos]);
    }

    // Helper para obtener usuario por token
    private function getUserByToken($token)
    {
        $accessToken = PersonalAccessToken::findToken($token);
        return $accessToken ? $accessToken->tokenable : null;
    }
    
}
