<?php

namespace App\Action\HRM;

use App\Models\Payroll;

class CreatePayrollAction
{
    public function execute(array $data)
    {
        $payrolls = [];

        foreach ($data['employee_ids'] as $index => $employee_id) {
            $payrolls[] = Payroll::create([
                'employee_id' => $employee_id,
                'basic_salary' => $data['basic_salaries'][$index],
                'allowance' => $data['allowances'][$index] ?? 0,
                'pay_date' => $data['pay_date'],
                'notes' => $data['notes'][$index] ?? null,
            ]);
        }

        return $payrolls;
    }
}
