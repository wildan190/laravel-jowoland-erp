<?php

namespace App\Http\Controllers\HRM;

use App\Action\HRM\CreateEmployeeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\EmployeeRequest;
use App\Models\Division;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('division');

        // 🔍 Pencarian umum (nama, email, phone)
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // 📌 Filter berdasarkan divisi
        if ($division_id = $request->get('division_id')) {
            $query->where('division_id', $division_id);
        }

        $employees = $query->orderBy('name')->paginate(10);

        $divisions = Division::orderBy('name')->get();

        return view('hrm.employees.index', compact('employees', 'divisions'));
    }

    public function create()
    {
        $divisions = Division::all();

        return view('hrm.employees.create', compact('divisions'));
    }

    public function edit(Employee $employee)
    {
        $divisions = Division::all();

        return view('hrm.employees.edit', compact('employee', 'divisions'));
    }

    public function store(EmployeeRequest $request, CreateEmployeeAction $action)
    {
        $action->execute($request->validated());

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil diperbarui');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus');
    }
}
