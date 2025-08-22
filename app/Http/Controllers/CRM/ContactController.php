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
    public function index(\Illuminate\Http\Request $request, IndexAction $action)
    {
        $query = Contact::query();

        // 🔍 Pencarian umum (lebih fleksibel)
        if ($search = $request->get('search')) {
            $search = strtolower($search); // case-insensitive

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(phone) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(company) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(address) LIKE ?', ["%{$search}%"]);
            });
        }

        // 📌 Filter perusahaan
        if ($company = $request->get('company')) {
            $query->whereRaw('LOWER(company) LIKE ?', ['%'.strtolower($company).'%']);
        }

        // 📌 Filter punya email
        if ($request->boolean('has_email')) {
            $query->whereNotNull('email')->where('email', '!=', '');
        }

        // 📌 Filter punya phone
        if ($request->boolean('has_phone')) {
            $query->whereNotNull('phone')->where('phone', '!=', '');
        }

        $contacts = $query->orderBy('name')->paginate(10)->withQueryString();

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
