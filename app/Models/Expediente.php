<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    protected $fillable = ['carro_id', 'cliente', 'telefono'];

    public function carro()
    {
        return $this->belongsTo(Carro::class);
    }

    public function archivos()
    {
        return $this->hasMany(ExpedienteArchivo::class);
    }
}