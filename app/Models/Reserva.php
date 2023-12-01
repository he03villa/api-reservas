<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_personas',
        'fecha_llegada',
        'cantidad_noche',
        'valor_reserva',
        'estado_reserva',
        'clientes_id'
    ];
}
