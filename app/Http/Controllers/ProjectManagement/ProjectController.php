<?php

namespace App\Http\Controllers\ProjectManagement;

use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectManagement\ProjectRequest;
use App\Action\ProjectManagement\CreateProjectAction;
use App\Action\ProjectManagement\UpdateProjectAction;
use App\Action\ProjectManagement\DeleteProjectAction;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('project_management.index', compact('projects'));
    }

    public function create()
    {
        return view('project_management.create');
    }

    public function store(ProjectRequest $request, CreateProjectAction $createAction)
    {
        $createAction->execute($request->validated());
        return redirect()->route('projects.index')->with('success', 'Proyek berhasil dibuat.');
    }

    public function edit(Project $project)
    {
        return view('project_management.edit', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project, UpdateProjectAction $updateAction)
    {
        $updateAction->execute($project, $request->validated());
        return redirect()->route('projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project, DeleteProjectAction $deleteAction)
    {
        $deleteAction->execute($project);
        return redirect()->route('projects.index')->with('success', 'Proyek berhasil dihapus.');
    }
}
