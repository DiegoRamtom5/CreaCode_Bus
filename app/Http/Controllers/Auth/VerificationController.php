<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Models\User;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {

            // Depuración
    return response()->json(['status' => 'Verificación accedida correctamente'], 200);

        // Obtiene el usuario con el ID proporcionado en la URL
        $user = User::findOrFail($request->route('id'));

        // Valida que el hash del enlace coincida con el correo del usuario
        if (!hash_equals($request->route('hash'), sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'El enlace de verificación no es válido.'], 400);
        }

        // Verifica si el correo ya está marcado como verificado
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'El correo ya ha sido verificado anteriormente.']);
        }

        // Marca el correo como verificado
        $user->markEmailAsVerified();

        // Dispara el evento de verificación de correo
        event(new Verified($user));

        return response()->json(['message' => 'Correo verificado con éxito.']);
    }
}
