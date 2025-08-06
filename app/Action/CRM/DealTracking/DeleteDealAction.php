<?php

namespace App\Action\CRM\DealTracking;

use App\Models\Deal;

class DeleteDealAction
{
    public function execute(Deal $deal): void
    {
        $deal->delete();
    }
}
