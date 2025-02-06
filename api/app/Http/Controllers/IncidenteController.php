<?php

namespace App\Http\Controllers;

use App\Models\Corrida;
use App\Models\Incidente;
use App\Models\Boleto;
use App\Models\Notificacion;
use Illuminate\Http\Request;

class IncidenteController extends Controller
{
    // Registrar incidente y notificar
    public function registrarIncidente(Request $request)
    {
        $validated = $request->validate([
            'id_corrida' => 'required|exists:corrida,id',
            'tipo_incidencia' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'evidencia' => 'nullable|file',
            'tiempo_estima_retraso' => 'required|date_format:H:i:s',
        ]);

        // Crear incidente
        $incidente = Incidente::create([
            'id_autobus' => Corrida::find($validated['id_corrida'])->id_autobus,
            'id_corrida' => $validated['id_corrida'],
            'tipo_incidencia' => $validated['tipo_incidencia'],
            'descripcion' => $validated['descripcion'],
            'evidencia' => $validated['evidencia'] ? $request->file('evidencia')->store('evidencias') : null,
            'tiempo_estima_retraso' => $validated['tiempo_estima_retraso'],
            'fecha' => now(),
        ]);

        // Notificar a usuarios afectados
        $boletos = Boleto::where('id_corrida', $validated['id_corrida'])->get();
        foreach ($boletos as $boleto) {
            Notificacion::create([
                'id_boleto' => $boleto->id,
                'id_incidente' => $incidente->id,
                'tipo' => 1, // Notificación de incidente
                'mensaje' => "Incidente registrado: {$validated['tipo_incidencia']} - {$validated['descripcion']}",
                'fecha_envio' => now(),
            ]);

            // Simular envío de mensaje al número del usuario
            $usuario = $boleto->usuario; // Relación usuario en el modelo Boleto
            $this->enviarMensaje($usuario->telefono, "Incidente en su corrida: {$validated['descripcion']}");
        }

        return response()->json(['message' => 'Incidente registrado y notificado']);
    }

    // Método para simular envío de mensaje
    private function enviarMensaje($telefono, $mensaje)
    {
        // Aquí puedes integrar un proveedor de SMS como Twilio o Nexmo
        \Log::info("Mensaje enviado a {$telefono}: {$mensaje}");
    }
}
