<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class boleto extends Model
{
    use HasFactory;

    protected $table = 'boleto';

    protected $fillable = [
        'num_boleto',
        'id_usuario',
        'id_corrida',
        'num_asientos',
        'fecha_compra',
        'monto',
        'descuento',
        'id_pago',
        'estado',
    ];

    protected function user(){
        return $this->belongsTo(user::class, 'id_usuario');
    }

    protected function corrida(){
        return $this->belongsTo(corrida::class, 'id_corrida');
    }
}
