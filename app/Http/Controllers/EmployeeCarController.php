<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Carro;
use Inertia\Inertia;

class EmployeeCarController extends Controller
{
    public function index(Employee $employee)
    {
        $employee->load('carros');

        return Inertia::render('Employees/Carros', [
            'employee' => $employee,
            'carros' => Carro::all()
        ]);
    }

    public function attach(Employee $employee, Carro $carro)
    {
        $employee->carros()->syncWithoutDetaching([
            $carro->id => ['pagado' => false]
        ]);

        return back();
    }

    public function toggle(Employee $employee, Carro $carro)
    {
        $relation = $employee->carros()
            ->where('carro_id', $carro->id)
            ->first();

        if ($relation) {
            $employee->carros()->updateExistingPivot(
                $carro->id,
                ['pagado' => !$relation->pivot->pagado]
            );
        }

        return back();
    }
}