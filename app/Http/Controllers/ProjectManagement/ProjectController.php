<?php

namespace App\Http\Controllers\ProjectManagement;

use App\Action\ProjectManagement\CreateProjectAction;
use App\Action\ProjectManagement\DeleteProjectAction;
use App\Action\ProjectManagement\UpdateProjectAction;
use App\Action\ProjectManagement\UpdateTaskStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectManagement\ProjectRequest;
use App\Models\Project;
use App\Models\ProjectTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount([
            'tasks as progress' => function ($q) {
                $q->select(DB::raw('round(100 * sum(is_done)/count(*))'));
            },
        ])->get();

        return view('project_management.index', compact('projects'));
    }

    public function create()
    {
        return view('project_management.create');
    }

    public function store(ProjectRequest $request, CreateProjectAction $createAction)
    {
        $project = $createAction->execute($request->validated());

        foreach ($request->input('tasks', []) as $task) {
            $project->tasks()->create(['task_name' => $task]);
        }

        return redirect()->route('projects.index')->with('success', 'Project created.');
    }

    public function edit(Project $project)
    {
        $project->load('tasks');

        return view('project_management.edit', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project, UpdateProjectAction $updateAction)
    {
        $updateAction->execute($project, $request->validated());
        foreach ($request->input('tasks_existing', []) as $taskId => $name) {
            $task = ProjectTask::find($taskId);
            if ($task) {
                $task->update(['task_name' => $name]);
            }
        }

        foreach ($request->input('tasks', []) as $taskName) {
            $project->tasks()->create(['task_name' => $taskName]);
        }

        return redirect()->route('projects.index')->with('success', 'Project updated.');
    }

    public function show(Project $project)
    {
        $project->load('tasks');

        return view('project_management.show', compact('project'));
    }

    public function destroy(Project $project, DeleteProjectAction $deleteAction)
    {
        try {
            $deleteAction->execute($project);

            return redirect()->route('projects.index')->with('success', 'Proyek berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal menghapus proyek.');
        }
    }

    public function updateTask(Request $request, ProjectTask $task, UpdateTaskStatusAction $updateAction)
    {
        $updateAction->execute($task, $request->boolean('is_done'));

        return response()->json(['success' => true]);
    }
}
