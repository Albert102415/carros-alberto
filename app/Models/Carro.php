<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Gasto;
use App\Models\Ganancia;

class Carro extends Model
{
    protected $table = 'carros';

    protected $fillable = [
        'marca',
        'linea',
        'modelo',
        'color',
        'proveedor',
        'precio_compra',
        'precio_venta',
        'estado',
        'imagen',
        'fecha_venta',
        'customer_id',
        'monto_pagado',
    ];

    protected $appends = [
        'costo_real',
        'ganancia_real',
        'deuda',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function gastos()
    {
        return $this->hasMany(Gasto::class);
    }

    public function ganancias()
    {
        return $this->hasOne(Ganancia::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ATRIBUTOS CALCULADOS
    |--------------------------------------------------------------------------
    */

    // 🔥 Precio compra + gastos
    public function getCostoRealAttribute()
    {
        return $this->precio_compra + $this->gastos->sum('monto');
    }

    // 🔥 Ganancia real cuando está vendido
    public function getGananciaRealAttribute()
    {
        if ($this->estado !== 'vendido' || !$this->precio_venta) {
            return null;
        }

        return $this->precio_venta - $this->costo_real;
    }

    // 🔥 Deuda del cliente
    public function getDeudaAttribute()
    {
        if ($this->estado !== 'vendido' || !$this->precio_venta) {
            return 0;
        }

        return max(0, $this->precio_venta - ($this->monto_pagado ?? 0));
    }

    /*
    |--------------------------------------------------------------------------
    | EVENTOS AUTOMÁTICOS
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::updating(function ($carro) {
            if ($carro->isDirty('estado') && $carro->estado === 'vendido') {
                $carro->fecha_venta = now();
            }
        });
    }
    /*
    |--------------------------------------------------------------------------
    | Realcion de carros con empleados
    |--------------------------------------------------------------------------
    */
    public function employees()
    {
        return $this->belongsToMany(
            \App\Models\Employee::class,
            'car_employee',
            'carro_id',
            'employee_id'
        )
            ->withPivot('pagado')
            ->withTimestamps();
    }
}