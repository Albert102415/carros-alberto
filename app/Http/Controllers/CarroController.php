<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Ganancia;

class CarroController extends Controller
{
    public function index()
    {
        return Inertia::render('Carros/Index', [
            'carros' => Carro::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Carros/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'marca' => 'required',
            'linea' => 'required',
            'modelo' => 'required',
            'anio' => 'required|integer',
            'precio_compra' => 'required|numeric',
            'estado' => 'required',
        ]);

        Carro::create($request->all());

        return redirect()->route('carros.index');
    }

    public function edit(Carro $carro)
    {
        return Inertia::render('Carros/Edit', [
            'carro' => $carro,
        ]);
    }

    public function update(Request $request, Carro $carro)
    {
        $data = $request->validate([
            'marca' => 'required|string|max:255',
            'linea' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'anio' => 'required|integer',
            'color' => 'nullable|string|max:255',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'nullable|numeric|min:0',
            'estado' => 'required|in:disponible,apartado,vendido',
            'fecha_venta' => 'nullable|date',
        ]);

        // Si se vende y no trae fecha, poner fecha actual
        if ($data['estado'] === 'vendido' && empty($data['fecha_venta'])) {
            $data['fecha_venta'] = now();
        }

        $carro->update($data);

        /* ===========================
           GENERAR GANANCIA AUTOMÁTICA
        ============================ */
        if ($data['estado'] === 'vendido' && $data['precio_venta']) {

            $totalGastos = $carro->gastos()->sum('monto');
            $totalInvertido = ($carro->precio_compra ?? 0) + $totalGastos;
            $gananciaFinal = $data['precio_venta'] - $totalInvertido;

            Ganancia::updateOrCreate(
                ['carro_id' => $carro->id],
                [
                    'total_invertido' => $totalInvertido,
                    'precio_venta' => $data['precio_venta'],
                    'ganancia' => $gananciaFinal,
                    'fecha_venta' => $data['fecha_venta'],
                ]
            );
        }

        return redirect()->route('carros.index');
    }


    public function destroy(Carro $carro)
    {
        $carro->delete();
        return redirect()->route('carros.index');
    }
    public function marcarVendido(Request $request, Carro $carro)
    {
        $carro->update([
            'estado' => 'vendido',
            'precio_venta' => $request->precio_venta,
            'fecha_venta' => now(), // 🔥 AQUÍ
        ]);

        return redirect()->route('ventas.index');
    }

}
