<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'nombre',
        'deuda_total',
        'abono',
    ];

    public function abonos()
    {
        return $this->hasMany(Abono::class);
    }
}