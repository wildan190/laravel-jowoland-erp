<?php

namespace App\Http\Controllers\CRM;

use App\Action\CRM\Pipeline\CreateStageAction;
use App\Action\CRM\Pipeline\DeleteStageAction;
use App\Action\CRM\Pipeline\UpdateStageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\PipelineStageRequest;
use App\Models\PipelineStage;

class PipelineStageController extends Controller
{
    public function index()
    {
        $stages = PipelineStage::orderBy('order')->get();

        return view('crm.pipeline_stages.index', compact('stages'));
    }

    public function create()
    {
        return view('crm.pipeline_stages.create');
    }

    public function store(PipelineStageRequest $request, CreateStageAction $createAction)
    {
        $createAction->execute($request->validated());

        return redirect()->route('pipeline.index')->with('success', 'Stage berhasil ditambahkan.');
    }

    public function edit(PipelineStage $stages)
    {
        return view('crm.pipeline_stages.edit', ['stages' => $stages]);
    }

    public function update(PipelineStageRequest $request, PipelineStage $stages, UpdateStageAction $updateAction)
    {
        $updateAction->execute($stages, $request->validated());

        return redirect()->route('pipeline.index')->with('success', 'Stage berhasil diperbarui.');
    }

    public function destroy(PipelineStage $stages, DeleteStageAction $deleteAction)
    {
        $deleteAction->execute($stages);

        return redirect()->route('pipeline.index')->with('success', 'Stage berhasil dihapus.');
    }
}
