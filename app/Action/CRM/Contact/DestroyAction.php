<?php

namespace App\Action\CRM\Contact;

use App\Models\Contact;

class DestroyAction
{
    public function handle(Contact $contact)
    {
        return $contact->delete();
    }
}
