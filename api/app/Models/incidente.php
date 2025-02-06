<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class incidente extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_autobus',
        'id_corrida',
        'tipo_incidencia',
        'descripcion',
        'evidencia',
        'tiempo_estima_retraso',
        'fecha',
    ];

    protected function autobus(){
        return $this->belongsTo(autobus::class, 'id_autobus');
    }

    protected function corrida(){
        return $this->belongsTo(corrida::class, 'id_corrida');
    }
}
