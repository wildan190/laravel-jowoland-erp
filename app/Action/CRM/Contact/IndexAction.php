<?php

namespace App\Action\CRM\Contact;

use App\Models\Contact;

class IndexAction
{
    public function handle()
    {
        return Contact::latest()->paginate(10);
    }
}
