<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Carro;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'employees' => Employee::all(),
            'carros' => Carro::all(),
        ]);
    }
}
