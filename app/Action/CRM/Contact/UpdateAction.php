<?php

namespace App\Action\CRM\Contact;

use App\Models\Contact;

class UpdateAction
{
    public function handle(Contact $contact, array $data)
    {
        $contact->update($data);

        return $contact;
    }
}
