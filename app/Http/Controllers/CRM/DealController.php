<?php

namespace App\Http\Controllers\CRM;

use App\Action\CRM\DealTracking\CreateDealAction;
use App\Action\CRM\DealTracking\DeleteDealAction;
use App\Action\CRM\DealTracking\UpdateDealAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\DealStoreRequest;
use App\Http\Requests\CRM\DealUpdateRequest;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\PipelineStage;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::with(['contact', 'stage'])
            ->latest()
            ->get();

        return view('crm.deals.index', compact('deals'));
    }

    public function create()
    {
        return view('crm.deals.create', [
            'contacts' => Contact::all(),
            'stages' => PipelineStage::orderBy('order')->get(),
        ]);
    }

    public function store(DealStoreRequest $request, CreateDealAction $createAction)
    {
        $createAction->execute($request->validated());

        return redirect()->route('deal.index')->with('success', 'Deal berhasil ditambahkan.');
    }

    public function edit(Deal $deal)
    {
        return view('crm.deals.edit', [
            'deal' => $deal,
            'contacts' => Contact::all(),
            'stages' => PipelineStage::orderBy('order')->get(),
        ]);
    }

    public function update(DealUpdateRequest $request, Deal $deal, UpdateDealAction $updateAction)
    {
        $updateAction->execute($deal, $request->validated());

        return redirect()->route('deal.index')->with('success', 'Deal berhasil diperbarui.');
    }

    public function kanban()
    {
        $stages = \App\Models\PipelineStage::with('deals.contact')->orderBy('order')->get();

        return view('crm.deals.kanban', compact('stages'));
    }

    public function move(Request $request, Deal $deal)
    {
        $request->validate([
            'stage_id' => 'required|exists:pipeline_stages,id',
        ]);

        $deal->pipeline_stage_id = $request->stage_id;
        $deal->save();

        return response()->json(['success' => true]);
    }

    public function destroy(Deal $deal, DeleteDealAction $deleteAction)
    {
        $deleteAction->execute($deal);

        return redirect()->route('deal.index')->with('success', 'Deal berhasil dihapus.');
    }
}
