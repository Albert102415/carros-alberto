<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Abono;

class CustomerController extends Controller
{
    public function index()
    {
        return Inertia::render('Customers/Index', [
            'customers' => Customer::with('abonos')
                ->orderByDesc('id')
                ->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Customers/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'deuda_total' => 'required|numeric|min:0'
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index');
    }

    public function edit(Customer $customer)
    {
        return Inertia::render('Customers/Edit', [
            'customer' => $customer
        ]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index');
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'nombre' => 'required',
            'abono' => 'nullable|numeric|min:0'
        ]);

        $customer->nombre = $request->nombre;

        if ($request->filled('abono') && $request->abono > 0) {

            // 🔥 Guardar abono en tabla
            Abono::create([
                'customer_id' => $customer->id,
                'customer_nombre' => $customer->nombre,
                'monto' => $request->abono,
            ]);

            // 🔥 Restar deuda
            $customer->deuda_total -= $request->abono;

            if ($customer->deuda_total < 0) {
                $customer->deuda_total = 0;
            }
        }

        $customer->save();

        return redirect()->route('customers.index');
    }
}