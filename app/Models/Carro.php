<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function employees()
    {
        return $this->belongsToMany(
            Employee::class,
            'car_employee',
            'carro_id',
            'employee_id'
        )
            ->withPivot('pagado')
            ->withTimestamps();
    }

    public function expediente()
    {
        return $this->hasOne(Expediente::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ATRIBUTOS CALCULADOS
    |--------------------------------------------------------------------------
    */

    public function getCostoRealAttribute(): float
    {
        return $this->precio_compra + $this->gastos->sum('monto');
    }

    public function getGananciaRealAttribute(): ?float
    {
        if ($this->estado !== 'vendido' || !$this->precio_venta) {
            return null;
        }

        return $this->precio_venta - $this->costo_real;
    }

    public function getDeudaAttribute(): float
    {
        if ($this->estado !== 'vendido' || !$this->precio_venta) {
            return 0;
        }

        return max(0, $this->precio_venta - ($this->monto_pagado ?? 0));
    }

    /*
    |--------------------------------------------------------------------------
    | EVENTOS
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::updating(function (self $carro) {
            if ($carro->isDirty('estado') && $carro->estado === 'vendido') {
                $carro->fecha_venta = now();
            }
        });
    }
}