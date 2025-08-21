<?php

namespace App\Http\Controllers\CRM;

use App\Action\CRM\UploadAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\UploadRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UploadController extends Controller
{
    public function index(): View
    {
        $uploads = \App\Models\RecommendationUpload::with('contact')->latest()->get();
        $contacts = \App\Models\Contact::all();

        return view('crm.upload', compact('uploads', 'contacts'));
    }

    public function store(UploadRequest $request, UploadAction $action): RedirectResponse
    {
        $action->execute($request->file('file'), $request->contact_id);

        return redirect()->back()->with('success', 'File berhasil diupload!');
    }
}
