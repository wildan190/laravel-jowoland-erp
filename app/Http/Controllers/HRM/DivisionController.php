<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\DivisionRequest;
use App\Models\Division;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::latest()->get();

        return view('hrm.divisions.index', compact('divisions'));
    }

    public function create()
    {
        return view('HRM.divisions.create');
    }

    public function store(DivisionRequest $request)
    {
        Division::create($request->validated());

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit(Division $division)
    {
        return view('HRM.divisions.edit', compact('division'));
    }

    public function update(DivisionRequest $request, Division $division)
    {
        $division->update($request->validated());

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Division $division)
    {
        $division->delete();

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dihapus.');
    }
}
