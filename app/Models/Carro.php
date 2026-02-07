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

    protected $appends = [
        'costo_real',
        'ganancia_real',
    ];

    public function gastos()
    {
        return $this->hasMany(Gasto::class);
    }

    // 🔥 PRECIO COMPRA + GASTOS
    public function getCostoRealAttribute()
    {
        return $this->precio_compra + $this->gastos->sum('monto');
    }

    // 🔥 GANANCIA FINAL
    public function getGananciaRealAttribute()
    {
        if ($this->estado !== 'vendido') {
            return null;
        }

        return $this->precio_venta - $this->costo_real;
    }
    protected static function booted()
    {
        static::updating(function ($carro) {
            if ($carro->isDirty('estado') && $carro->estado === 'vendido') {
                $carro->fecha_venta = now();
            }
        });
    }

}

