<?php

namespace App\Http\Controllers\ProjectManagement;

use App\Action\ProjectManagement\CreateProjectAction;
use App\Action\ProjectManagement\DeleteProjectAction;
use App\Action\ProjectManagement\UpdateProjectAction;
use App\Action\ProjectManagement\UpdateTaskStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectManagement\ProjectRequest;
use App\Models\Contact;
use App\Models\Project;
use App\Models\ProjectTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index(Request $request)
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
        $contacts = Contact::orderBy('name')->get();

        return view('project_management.create', compact('contacts'));
    }

    public function store(ProjectRequest $request, CreateProjectAction $createAction)
    {
        $data = $request->validated();
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;

        $project = $createAction->execute($data);

        foreach ($request->tasks as $i => $name) {
            $project->tasks()->create([
                'task_name' => $name,
                'due_date' => $request->tasks_due_date[$i] ?? null,
            ]);
        }

        return redirect()->route('projects.index')->with('success', 'Project created.');
    }

    public function edit(Project $project)
    {
        $project->load('tasks', 'contact'); // Eager load
        $contacts = Contact::orderBy('name')->get();

        return view('project_management.edit', compact('project', 'contacts'));
    }

    public function update(ProjectRequest $request, Project $project, UpdateProjectAction $updateAction)
    {
        $data = $request->validated();
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        $data['tasks_existing'] = $request->input('tasks_existing', []);
        $data['tasks_existing_due_date'] = $request->input('tasks_existing_due_date', []);
        $data['tasks'] = $request->input('tasks', []);
        $data['tasks_due_date'] = $request->input('tasks_due_date', []);

        $updateAction->execute($project, $data);

        return redirect()->route('projects.index')->with('success', 'Project updated.');
    }

    public function show(Project $project)
    {
        $project->load('tasks');

        return view('project_management.show', compact('project'));
    }

    public function calendar(Project $project)
    {
        $tasks = $project->tasks()->get(['id', 'task_name', 'due_date', 'created_at']);
        $events = $tasks->map(
            fn ($task) => [
                'id' => $task->id,
                'title' => $task->task_name,
                'start' => $task->created_at->toDateString(),
                'end' => $task->due_date,
            ],
        );

        return view('project_management.calendar', compact('project', 'events'));
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
