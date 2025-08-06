<?php

namespace App\Action\ProjectManagement;

use App\Models\Project;

class DeleteProjectAction
{
    public function execute(Project $project): bool
    {
        return $project->delete();
    }
}
