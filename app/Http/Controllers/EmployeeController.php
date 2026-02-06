<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
                return Inertia::render('Employees/Index', [
            'employees' => Employee::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Employees/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'position' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
        ]);
        Employee::create($request->all());
        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit(string $id)
{
    $employee = Employee::findOrFail($id); // Busca el empleado o lanza 404
    return Inertia::render('Employees/Edit', [
         'employee' => $employee, 
    ]);
}
    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, string $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'position' => 'required|string|max:255',
        'salary' => 'required|numeric|min:0',
    ]);

    $employee = Employee::findOrFail($id); // Busca el empleado
    $employee->update($request->all());

    return redirect()->route('employees.index');
}

public function destroy(string $id): RedirectResponse
{
    $employee = Employee::findOrFail($id);
    $employee->delete();

    return redirect()->route('employees.index');

}

}