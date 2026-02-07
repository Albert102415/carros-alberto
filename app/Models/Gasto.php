<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $fillable = [
        'carro_id',
        'concepto',
        'monto',
    ];

    public function carro()
    {
        return $this->belongsTo(Carro::class);
    }
}
