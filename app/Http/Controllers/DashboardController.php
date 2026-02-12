<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Carro;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Traemos carros con gastos
        $carros = Carro::with('gastos')->get();

        // Separaciones
        $disponibles = $carros->where('estado', '!=', 'vendido');
        $vendidos = $carros->where('estado', 'vendido');

        return Inertia::render('Dashboard', [

            /* =====================
               DATA BASE
               ===================== */
            'carros' => $carros,
            'employees' => Employee::all(),

            /* =====================
               KPIs
               ===================== */
            // Inventario real (precio compra + gastos)
            'totalInventario' => $disponibles->sum(
                fn($c) => $c->costo_real
            ),

            // Ganancia real (venta - costo real)
            'totalGanado' => $vendidos->sum(
                fn($c) => $c->ganancia_real
            ),

            // Total de gastos generales
            'totalGastos' => $carros->sum(
                fn($c) => $c->gastos->sum('monto')
            ),

            // Cantidades
            'cantidadDisponibles' => $disponibles->count(),
            'cantidadVendidos' => $vendidos->count(),
        ]);
    }
}
