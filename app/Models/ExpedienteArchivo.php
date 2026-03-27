<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpedienteArchivo extends Model
{
    protected $fillable = ['expediente_id', 'nombre', 'archivo', 'tipo'];

    public function expediente()
    {
        return $this->belongsTo(Expediente::class);
    }
}