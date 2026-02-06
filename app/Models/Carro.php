<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    protected $fillable = [
        'marca',
        'linea',
        'modelo',
        'anio',
        'color',
        'precio_compra',
        'precio_venta',
        'estado',
        'fecha_venta',
    ];

    public function getGananciaAttribute()
    {
        if ($this->estado === 'vendido' && $this->precio_venta && $this->precio_compra) {
            return $this->precio_venta - $this->precio_compra;
        }

        return null;
    }
}
