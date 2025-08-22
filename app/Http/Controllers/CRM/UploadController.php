<?php

namespace App\Http\Controllers\CRM;

use App\Action\CRM\UploadAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\UploadRequest;
use App\Models\Contact;
use App\Models\RecommendationUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UploadController extends Controller
{
    public function index(): View
    {
        $query = RecommendationUpload::with('contact')->latest();

        // 🔍 Filter search keyword (nama file)
        if ($search = request()->get('search')) {
            $query->where('file_name', 'like', "%{$search}%");
        }

        // 📌 Filter by contact
        if ($contactId = request()->get('contact_id')) {
            $query->where('contact_id', $contactId);
        }

        // 📌 Filter by upload date range
        if ($startDate = request()->get('start_date')) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate = request()->get('end_date')) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Pagination 10 per halaman
        $uploads = $query->paginate(10)->appends(request()->query());

        $contacts = Contact::all();

        return view('crm.upload', compact('uploads', 'contacts'));
    }

    public function store(UploadRequest $request, UploadAction $action): RedirectResponse
    {
        $action->execute($request->file('file'), $request->contact_id);

        return redirect()->back()->with('success', 'File berhasil diupload!');
    }
}
