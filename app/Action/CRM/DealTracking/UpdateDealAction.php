<?php

namespace App\Action\CRM\DealTracking;

use App\Models\Deal;

class UpdateDealAction
{
    public function execute(Deal $deal, array $data): Deal
    {
        $deal->update($data);

        return $deal;
    }
}
