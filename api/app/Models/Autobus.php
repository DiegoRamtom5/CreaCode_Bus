<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autobus extends Model
{
    use HasFactory;

    // Especificamos el nombre de la tabla, ya que en este caso es singular
    protected $table = 'autobus';

    protected $fillable = [
        'numero_autobus',
        'linea',
        'capacidad',
        'servicios',
        'num_incidencia',
    ];
}
