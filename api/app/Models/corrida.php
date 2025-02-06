<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corrida extends Model
{
    use HasFactory;

    protected $table = 'corrida';

    protected $fillable = [
        'id_autobus',
        'origen',
        'destino',
        'fecha',
        'hora_salida',
        'hora_estima_llegada',
        'tipo_corrida',
        'asientos',
        'precio',
    ];

    protected $casts = [
        'asientos' => 'array', // Mantén esta definición
    ];
}
