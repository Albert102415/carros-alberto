<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'email',
        'position',
        'salary',
    ];

    public function carros()
    {
        return $this->belongsToMany(
            \App\Models\Carro::class,
            'car_employee',
            'employee_id',
            'carro_id'
        )
            ->withPivot('pagado')
            ->withTimestamps();
    }
}