<?php

namespace App\Action\HRM;

use App\Models\Payroll;

class CreatePayrollAction
{
    public function execute(array $data): Payroll
    {
        return Payroll::create($data);
    }
}
