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
        $uploads = \App\Models\RecommendationUpload::latest()->get();
        return view('crm.upload', compact('uploads'));
    }

    public function store(UploadRequest $request, UploadAction $action): RedirectResponse
    {
        $action->execute($request->file('file'));

        return redirect()->back()->with('success', 'File berhasil diupload!');
    }
}
