<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ganancia extends Model
{
    protected $fillable = [
        'carro_id',
        'total_invertido',
        'precio_venta',
        'ganancia',
        'fecha_venta',
    ];

    public function carro()
    {
        return $this->belongsTo(Carro::class);
    }
}
