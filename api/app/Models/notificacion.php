<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notificacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_boleto',
        'id_incidente',
        'tipo',
        'mensaje',
        'fecha_envio',
    ];

    protected function boleto(){
        return $this->belongsTo(boleto::class, 'id_boleto');
    }

    protected function incidente(){
        return $this->belongsTo(incidente::class, 'id_incidente');
    }
}
