<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Carro;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Traemos carros con gastos
        $carros = Carro::with('gastos')->get();

        // Separaciones
        $disponibles = $carros->where('estado', '!=', 'vendido');
        $vendidos = $carros->where('estado', 'vendido');

        /* =====================
           NUEVOS: datos extra
        ===================== */

        // Ventas por mes (últimos 7 meses)
        $ventasPorMes = $vendidos
            ->filter(fn($c) => $c->fecha_venta)
            ->groupBy(fn($c) => Carbon::parse($c->fecha_venta)->format('Y-m'))
            ->map(fn($grupo, $key) => [
                'mes' => Carbon::parse($key)->locale('es')->isoFormat('MMM YY'),
                'ganancia' => $grupo->sum(fn($c) => $c->ganancia_real),
                'unidades' => $grupo->count(),
            ])
            ->sortKeys()
            ->slice(-7)
            ->values();

        // Carros en taller / apartados
        $enTaller = $carros->where('estado', 'taller')->count();
        $apartados = $carros->where('estado', 'apartado')->count();

        // Ventas este mes
        $inicioMes = Carbon::now()->startOfMonth();
        $ventasEsteMes = $vendidos->filter(
            fn($c) => $c->fecha_venta && Carbon::parse($c->fecha_venta)->gte($inicioMes)
        );

        return Inertia::render('Dashboard', [

            /* =====================
               DATA BASE (igual que antes)
            ===================== */
            'carros' => $carros,
            'employees' => Employee::all(),

            /* =====================
               KPIs (igual que antes)
            ===================== */
            'totalInventario' => $disponibles->sum(fn($c) => $c->costo_real),
            'totalGanado' => $vendidos->sum(fn($c) => $c->ganancia_real),
            'totalGastos' => $carros->sum(fn($c) => $c->gastos->sum('monto')),
            'cantidadDisponibles' => $disponibles->count(),
            'cantidadVendidos' => $vendidos->count(),

            /* =====================
               KPIs NUEVOS
            ===================== */
            'enTaller' => $enTaller,
            'apartados' => $apartados,
            'ventasEsteMes' => $ventasEsteMes->count(),
            'gananciaEsteMes' => $ventasEsteMes->sum(fn($c) => $c->ganancia_real),
            'promedioGanancia' => $ventasEsteMes->count() > 0
                ? $ventasEsteMes->sum(fn($c) => $c->ganancia_real) / $ventasEsteMes->count()
                : 0,
            'ventasPorMes' => $ventasPorMes,

            // Ranking (antes lo calculabas en el frontend, ahora viene del backend)
            'rankingCarros' => $vendidos
                ->sortByDesc(fn($c) => $c->ganancia_real)
                ->take(5)
                ->map(fn($c) => [
                    'id' => $c->id,
                    'nombre' => "{$c->marca} {$c->linea} {$c->modelo}",
                    'ganancia' => $c->ganancia_real,
                ])
                ->values(),
        ]);
    }
}