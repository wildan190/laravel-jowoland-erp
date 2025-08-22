<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\AdsPlanRequest;
use App\Models\AdsPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdsPlanController extends Controller
{
    public function index(): View
    {
        $adsPlans = AdsPlan::latest()->get();

        return view('crm.ads_plans.index', compact('adsPlans'));
    }

    public function create(): View
    {
        return view('crm.ads_plans.create');
    }

    public function store(AdsPlanRequest $request): RedirectResponse
    {
        AdsPlan::create([
            ...$request->validated(),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('crm.ads_plans.index')
            ->with('success', 'Ads plan berhasil dibuat!');
    }

    public function edit(AdsPlan $adsPlan): View
    {
        return view('crm.ads_plans.edit', compact('adsPlan'));
    }

    public function update(AdsPlanRequest $request, AdsPlan $adsPlan): RedirectResponse
    {
        $adsPlan->update($request->validated());

        return redirect()->route('crm.ads_plans.index')
            ->with('success', 'Ads plan berhasil diperbarui!');
    }

    public function destroy(AdsPlan $adsPlan): RedirectResponse
    {
        $adsPlan->delete();

        return redirect()->route('crm.ads_plans.index')
            ->with('success', 'Ads plan berhasil dihapus!');
    }
}
