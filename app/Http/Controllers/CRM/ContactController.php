<?php

namespace App\Http\Controllers\CRM;

use App\Action\CRM\Contact\DestroyAction;
use App\Action\CRM\Contact\IndexAction;
use App\Action\CRM\Contact\StoreAction;
use App\Action\CRM\Contact\UpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\ContactRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index(IndexAction $action)
    {
        $contacts = $action->handle();

        return view('crm.contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('crm.contacts.create');
    }

    public function store(ContactRequest $request, StoreAction $action)
    {
        $action->handle($request->validated());

        return redirect()->route('contacts.index')->with('success', 'Contact added successfully!');
    }

    public function edit(Contact $contact)
    {
        return view('crm.contacts.edit', compact('contact'));
    }

    public function update(ContactRequest $request, Contact $contact, UpdateAction $action)
    {
        $action->handle($contact, $request->validated());

        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully!');
    }

    public function destroy(Contact $contact, DestroyAction $action)
    {
        $action->handle($contact);

        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully!');
    }
}
