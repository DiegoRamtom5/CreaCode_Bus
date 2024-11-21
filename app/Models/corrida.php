<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class corrida extends Model
{
    use HasFactory;

    protected $fillable = [
        'origen',
        'destino',
        'fecha_corrida',
        'hora_salida',
        'hora_estima_llegada',
        'tipo_corrida',
        'asientos',
        'precio',
        'id_autobus',
    ];

    protected function autobus(){
        return $this->belongsTo(autobus::class, 'id_autobus');
    }
}
