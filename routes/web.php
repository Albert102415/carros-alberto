<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeCarController;
use App\Http\Controllers\CarroController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpedienteController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    /* =========================
       DASHBOARD
    ========================= */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /* =========================
       CARROS
    ========================= */
    Route::get('/carros', [CarroController::class, 'index'])->name('carros.index');
    Route::get('/carros/create', [CarroController::class, 'create'])->name('carros.create');
    Route::post('/carros', [CarroController::class, 'store'])->name('carros.store');
    Route::get('/carros/{carro}/edit', [CarroController::class, 'edit'])->name('carros.edit');
    Route::put('/carros/{carro}', [CarroController::class, 'update'])->name('carros.update');
    Route::delete('/carros/{carro}', [CarroController::class, 'destroy'])->name('carros.destroy');
    Route::get('/carros/{carro}/contrato', [CarroController::class, 'contrato'])->name('carros.contrato');

    /* =========================
       GASTOS
    ========================= */
    Route::post('/carros/{carro}/gastos', [GastoController::class, 'store']);
    Route::delete('/gastos/{gasto}', [GastoController::class, 'destroy']);

    /* =========================
       EXPEDIENTE
    ========================= */
    Route::get('/carros/{carro}/expediente', [ExpedienteController::class, 'show'])->name('carros.expediente');
    Route::post('/carros/{carro}/expediente', [ExpedienteController::class, 'guardar']);
    Route::post('/carros/{carro}/expediente/archivo', [ExpedienteController::class, 'subirArchivo']);
    Route::delete('/expediente/archivo/{archivo}', [ExpedienteController::class, 'eliminarArchivo']);

    /* =========================
       EMPLEADOS
    ========================= */
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::get('/employees/{employee}/carros', [EmployeeCarController::class, 'index']);
    Route::post('/employees/{employee}/carros/{carro}/attach', [EmployeeCarController::class, 'attach']);
    Route::post('/employees/{employee}/carros/{carro}/toggle', [EmployeeCarController::class, 'toggle']);

    /* =========================
       VENTAS
    ========================= */
    Route::get('/ventas', function () {
        return Inertia::render('Ventas/Index', [
            'ventas' => \App\Models\Carro::where('estado', 'vendido')
                ->orderByDesc('fecha_venta')
                ->get(),
        ]);
    })->name('ventas.index');

    Route::get('/ventas/{carro}/pdf', [\App\Http\Controllers\VentaPdfController::class, 'show'])->name('ventas.pdf');

    /* =========================
       CLIENTES
    ========================= */
    Route::resource('customers', CustomerController::class);

    /* =========================
       USUARIOS (solo admin)
    ========================= */
    Route::get('/usuarios', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'index'])->name('usuarios.index');
    Route::post('/usuarios', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])->name('usuarios.store');
    Route::delete('/usuarios/{user}', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'destroy'])->name('usuarios.destroy');

});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';