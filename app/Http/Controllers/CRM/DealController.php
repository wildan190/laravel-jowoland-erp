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
    public function index(Request $request)
    {
        $query = Deal::with(['contact', 'stage']);

        // 🔍 Pencarian umum
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%")
                    ->orWhereHas('contact', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('stage', function ($q3) use ($search) {
                        $q3->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // 📌 Filter berdasarkan stage
        if ($stageId = $request->get('stage_id')) {
            $query->where('pipeline_stage_id', $stageId);
        }

        // 📌 Filter berdasarkan contact
        if ($contactId = $request->get('contact_id')) {
            $query->where('contact_id', $contactId);
        }

        // 📌 Urutkan dari terbaru + pagination
        $deals = $query
            ->latest()
            ->paginate(10)
            ->appends(request()->query());

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
