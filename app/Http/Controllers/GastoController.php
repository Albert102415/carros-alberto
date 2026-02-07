<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Models\Gasto;
use Illuminate\Http\Request;

class GastoController extends Controller
{
    public function store(Request $request, Carro $carro)
    {
        $request->validate([
            'concepto' => 'required|string',
            'monto' => 'required|numeric|min:1',
        ]);

        $carro->gastos()->create([
            'concepto' => $request->concepto,
            'monto' => $request->monto,
        ]);

        return back();
    }

    public function destroy(Gasto $gasto)
    {
        $gasto->delete();
        return back();
    }
}

