<?php

namespace App\Action\HRM;

use App\Models\Division;

class CreateDivisionAction
{
    public function execute(array $data): Division
    {
        return Division::create($data);
    }
}
