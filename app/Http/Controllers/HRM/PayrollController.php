<?php

namespace App\Http\Controllers\HRM;

use App\Action\HRM\CreatePayrollAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\PayrollRequest;
use App\Models\Employee;
use App\Models\Payroll;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::with('employee')->latest()->get();

        return view('HRM.payrolls.index', compact('payrolls'));
    }

    public function create()
    {
        $employees = Employee::all();

        return view('HRM.payrolls.create', compact('employees'));
    }

    public function store(PayrollRequest $request)
    {
        $data = $request->validated();

        $action = new CreatePayrollAction();
        $action->execute($data);

        return redirect()->route('payrolls.index')->with('success', 'Penggajian berhasil ditambahkan.');
    }

    public function edit(Payroll $payroll)
    {
        $employees = Employee::all();

        return view('HRM.payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(PayrollRequest $request, Payroll $payroll)
    {
        $payroll->update($request->validated());

        return redirect()->route('payrolls.index')->with('success', 'Data penggajian diperbarui.');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return redirect()->route('payrolls.index')->with('success', 'Data penggajian dihapus.');
    }
}
