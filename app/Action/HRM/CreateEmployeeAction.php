<?php

namespace App\Action\HRM;

use App\Models\Employee;

class CreateEmployeeAction
{
    public function execute(array $data): Employee
    {
        return Employee::create($data);
    }
}
