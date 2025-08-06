<?php

namespace App\Action\ProjectManagement;

use App\Models\ProjectTask;

class UpdateTaskStatusAction
{
    public function execute(ProjectTask $task, bool $status): void
    {
        $task->update(['is_done' => $status]);
    }
}
