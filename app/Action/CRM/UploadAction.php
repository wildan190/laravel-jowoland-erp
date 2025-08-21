<?php

namespace App\Action\CRM;

use App\Models\RecommendationUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadAction
{
    public function execute(UploadedFile $file, int $contactId): RecommendationUpload
    {
        // Simpan file ke storage/app/public/recommendations
        $path = $file->store('recommendations', 'public');

        return RecommendationUpload::create([
            'contact_id' => $contactId,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'uploaded_by' => Auth::id(),
        ]);
    }
}
