<?php

namespace App\Action\CRM\Contact;

use App\Models\Contact;

class StoreAction
{
    public function handle(array $data)
    {
        return Contact::create($data);
    }
}
