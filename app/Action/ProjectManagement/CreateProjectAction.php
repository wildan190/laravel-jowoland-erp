<?php

namespace App\Action\ProjectManagement;

use App\Models\Project;

class CreateProjectAction
{
    public function execute(array $data): Project
    {
        return Project::create($data);
    }
}
