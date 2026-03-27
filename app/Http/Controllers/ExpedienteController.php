<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Models\Expediente;
use App\Models\ExpedienteArchivo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpedienteController extends Controller
{
    public function show(Carro $carro)
    {
        $carro->load('gastos', 'expediente.archivos');

        // Si no tiene expediente lo crea vacío
        if (!$carro->expediente) {
            $carro->expediente()->create([
                'cliente' => null,
                'telefono' => null,
            ]);
            $carro->load('expediente.archivos');
        }

        return Inertia::render('Carros/Expediente', [
            'carro' => $carro,
        ]);
    }

    public function guardar(Request $request, Carro $carro)
    {
        $request->validate([
            'cliente' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $carro->expediente()->updateOrCreate(
            ['carro_id' => $carro->id],
            [
                'cliente' => $request->cliente,
                'telefono' => $request->telefono,
            ]
        );

        return back()->with('success', 'Expediente guardado');
    }

    public function subirArchivo(Request $request, Carro $carro)
    {
        $request->validate([
            'archivo' => 'required|file|max:10240',
            'nombre' => 'required|string|max:255',
            'tipo' => 'nullable|string|max:50',
        ]);

        $expediente = $carro->expediente ?? $carro->expediente()->create([]);

        $path = $request->file('archivo')->store('expedientes', 'public');

        $expediente->archivos()->create([
            'nombre' => $request->nombre,
            'archivo' => $path,
            'tipo' => $request->tipo ?? 'otro',
        ]);

        return back()->with('success', 'Archivo subido');
    }

    public function eliminarArchivo(ExpedienteArchivo $archivo)
    {
        \Storage::disk('public')->delete($archivo->archivo);
        $archivo->delete();

        return back()->with('success', 'Archivo eliminado');
    }
}