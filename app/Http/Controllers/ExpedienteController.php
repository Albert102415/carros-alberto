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
                'domicilio' => null,
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
            'domicilio' => 'nullable|string|max:255',
        ]);

        $carro->expediente()->updateOrCreate(
            ['carro_id' => $carro->id],
            [
                'cliente' => $request->cliente,
                'telefono' => $request->telefono,
                'domicilio' => $request->domicilio,
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
    /* ============================
       CONTRATO
    ============================ */
    public function generarContrato(Carro $carro)
    {
        $carro->load('gastos', 'expediente');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.contrato', compact('carro'))
            ->setPaper('letter', 'portrait');

        // Guardar ruta en expediente
        $nombreArchivo = "contrato_{$carro->marca}_{$carro->modelo}_{$carro->id}.pdf";
        $path = "expedientes/contratos/{$nombreArchivo}";

        \Storage::disk('public')->put($path, $pdf->output());

        // Guardar en tabla expedientes
        if ($carro->expediente) {
            $carro->expediente->update(['contrato' => $path]);
        }

        return $pdf->stream($nombreArchivo);
    }
}