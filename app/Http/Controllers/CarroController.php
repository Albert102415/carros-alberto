<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Ganancia;
use Illuminate\Support\Facades\Storage;

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

    /* ============================
       STORE
    ============================ */
    public function store(Request $request)
    {
        $data = $request->validate([
            'marca' => 'required',
            'linea' => 'required',
            'modelo' => 'required',
            'proveedor' => 'required',
            'precio_compra' => 'required|numeric',
            'estado' => 'required',
            'imagen' => 'nullable|image|max:2048',
        ]);

        // 🔥 GUARDAR IMAGEN
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('carros', 'public');
        }

        Carro::create($data);

        return redirect()->route('carros.index');
    }

    public function edit(Carro $carro)
    {
        return Inertia::render('Carros/Edit', [
            'carro' => $carro->load('gastos'),
        ]);
    }

    /* ============================
       UPDATE
    ============================ */
    public function update(Request $request, Carro $carro)
    {
        $data = $request->validate([
            'marca' => 'required|string|max:255',
            'linea' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'color' => 'nullable|string|max:255',
            'proveedor' => 'nullable|string|max:30',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'nullable|numeric|min:0',
            'estado' => 'required|in:disponible,apartado,vendido',
            'fecha_venta' => 'nullable|date',
            'imagen' => 'nullable|image|max:2048',
        ]);

        // 🔥 SI SE VENDE Y NO TRAE FECHA
        if ($data['estado'] === 'vendido' && empty($data['fecha_venta'])) {
            $data['fecha_venta'] = now();
        }

        // 🔥 MANEJO DE IMAGEN
        if ($request->hasFile('imagen')) {

            if ($carro->imagen) {
                Storage::disk('public')->delete($carro->imagen);
            }

            $data['imagen'] = $request->file('imagen')->store('carros', 'public');
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
        if ($carro->imagen) {
            Storage::disk('public')->delete($carro->imagen);
        }

        $carro->delete();

        return redirect()->route('carros.index');
    }

    public function marcarVendido(Request $request, Carro $carro)
    {
        $carro->update([
            'estado' => 'vendido',
            'precio_venta' => $request->precio_venta,
            'fecha_venta' => now(),
        ]);

        return redirect()->route('ventas.index');
    }
}