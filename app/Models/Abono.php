<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    protected $fillable = [
        'customer_id',
        'customer_nombre',
        'monto',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}