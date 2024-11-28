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
        // Obtiene el usuario con el ID proporcionado en la URL
        $user = User::findOrFail($request->route('id'));

        // Verifica si ya está marcado como verificado
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'El correo ya está verificado.']);
        }

        // Marca el correo como verificado
        if ($user->markEmailAsVerified()) {
            // Dispara el evento de verificación de correo
            event(new Verified($user));
        }

        return response()->json(['message' => 'Correo verificado con éxito.']);
    }
}
