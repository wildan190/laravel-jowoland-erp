<?php

namespace App\Action\ProjectManagement;

use App\Models\Project;
use App\Models\ProjectTask;

class UpdateProjectAction
{
    public function execute(Project $project, array $data): bool
    {
        $project->update($data);

        // Update & delete tasks
        $existingIds = array_keys($data['tasks_existing'] ?? []);
        $project->tasks()->whereNotIn('id', $existingIds)->delete();

        foreach ($data['tasks_existing'] ?? [] as $id => $name) {
            $task = ProjectTask::find($id);
            if ($task) {
                $task->update([
                    'task_name' => $name,
                    'due_date' => $data['tasks_existing_due_date'][$id] ?? null,
                ]);
            }
        }

        // Create new tasks
        foreach ($data['tasks'] ?? [] as $i => $name) {
            $project->tasks()->create([
                'task_name' => $name,
                'due_date' => $data['tasks_due_date'][$i] ?? null,
            ]);
        }

        return true;
    }
}
