<?php

namespace App\Action\CRM\DealTracking;

use App\Models\Deal;

class CreateDealAction
{
    public function execute(array $data): Deal
    {
        return Deal::create($data);
    }
}
