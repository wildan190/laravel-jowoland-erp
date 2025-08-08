<?php

namespace App\Action\Accounting;

use App\Models\Payroll;

class CalculatePayrollTotal
{
    public function execute(): float
    {
        return Payroll::with('employee')
            ->get()
            ->sum(fn ($payroll) => ($payroll->employee->salary ?? 0) + ($payroll->allowance ?? 0));
    }
}
