<?php

namespace App\Action\ProjectManagement;

use App\Models\Project;
use Illuminate\Support\Facades\DB;

class DeleteProjectAction
{
    public function execute(Project $project): void
    {
        DB::transaction(function () use ($project) {
            $project->tasks()->delete();  // hapus semua task terkait
            $project->delete();           // hapus proyek
        });
    }
}
