<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CarroController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GastoController;

Route::post('/carros/{carro}/gastos', [GastoController::class, 'store']);
Route::delete('/gastos/{gasto}', [GastoController::class, 'destroy']);


Route::post('/carros/{carro}/gastos', [GastoController::class, 'store']);
Route::delete('/gastos/{id}', [GastoController::class, 'destroy']);


Route::middleware(['auth', 'verified'])->get(
    '/dashboard',
    [DashboardController::class, 'index']
)->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/carros', [CarroController::class, 'index'])->name('carros.index');
    Route::get('/carros/create', [CarroController::class, 'create'])->name('carros.create');
    Route::post('/carros', [CarroController::class, 'store'])->name('carros.store');
    Route::get('/carros/{carro}/edit', [CarroController::class, 'edit'])->name('carros.edit');
    Route::put('/carros/{carro}', [CarroController::class, 'update'])->name('carros.update');
    Route::delete('/carros/{carro}', [CarroController::class, 'destroy'])->name('carros.destroy');
});

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
});

Route::middleware(['auth', 'verified'])->get('/ventas', function () {
    return Inertia::render('Ventas/Index', [
        'ventas' => \App\Models\Carro::where('estado', 'vendido')
            ->orderByDesc('fecha_venta')
            ->get(),
    ]);
})->name('ventas.index');

Route::middleware(['auth', 'verified'])->get(
    '/ventas/{carro}/pdf',
    [\App\Http\Controllers\VentaPdfController::class, 'show']
)->name('ventas.pdf');



require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
