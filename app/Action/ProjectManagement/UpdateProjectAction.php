<?php

namespace App\Action\ProjectManagement;

use App\Models\Project;

class UpdateProjectAction
{
    public function execute(Project $project, array $data): bool
    {
        return $project->update($data);
    }
}
