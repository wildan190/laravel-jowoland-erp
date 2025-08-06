<?php

namespace App\Http\Controllers\ProjectManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectManagement\ProjectProgressRequest;
use App\Models\Project;

class ProjectProgressController extends Controller
{
    public function index(Project $project)
    {
        $progresses = $project->progresses()->latest()->get();

        return view('project_management.progress.index', compact('project', 'progresses'));
    }

    public function store(ProjectProgressRequest $request, Project $project)
    {
        $project->progresses()->create($request->validated());

        return back()->with('success', 'Progress berhasil ditambahkan.');
    }
}
