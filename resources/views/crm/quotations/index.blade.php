@extends('layouts.app')

@section('title', 'Daftar Quotation')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Quotation</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
            <div>
                <i class="fa fa-file-invoice me-1"></i> Daftar Quotation
            </div>
            <a href="{{ route('crm.quotations.create') }}" class="btn btn-sm btn-warning">
                <i class="fa fa-plus me-1"></i> Tambah Quotation
            </a>
        </div>

        <div class="card-body">
            {{-- 🔍 Form Search & Filter --}}
            <form method="GET" action="{{ route('crm.quotations.index') }}" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control form-control-sm"
                           placeholder="Cari no. quotation / customer">
                </div>

                <div class="col-md-2">
                    <select name="category" class="form-select form-select-sm">
                        <option value="">-- Semua Kategori --</option>
                        <option value="hydraulic" {{ request('category')=='hydraulic' ? 'selected' : '' }}>Hydraulic</option>
                        <option value="mini_crane" {{ request('category')=='mini_crane' ? 'selected' : '' }}>Mini Crane</option>
                        <option value="strauss" {{ request('category')=='strauss' ? 'selected' : '' }}>Strauss</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-md-3 text-end">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa fa-search"></i> Filter
                    </button>
                    <a href="{{ route('crm.quotations.index') }}" class="btn btn-sm btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>

            <table class="table table-bordered table-striped table-sm align-middle">
                <thead class="table-light">
                    <tr class="text-center">
                        <th style="width:5%">No</th>
                        <th style="width:20%">Customer</th>
                        <th style="width:25%">No. Quotation</th>
                        <th style="width:15%">Tanggal</th>
                        <th style="width:15%">Total</th>
                        <th style="width:20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quotations as $q)
                        <tr>
                            <td class="text-center">{{ $loop->iteration + ($quotations->firstItem() - 1) }}</td>
                            <td>{{ $q->contact->name }}</td>
                            <td class="text-monospace">{{ $q->quotation_number }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($q->quotation_date)->format('d/m/Y') }}</td>
                            <td class="text-end">{{ number_format($q->total, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('crm.quotations.exportPdf', $q->id) }}" 
                                   class="btn btn-sm btn-secondary" 
                                   title="Export PDF">
                                    <i class="fa fa-file-pdf"></i>
                                </a>

                                <form action="{{ route('crm.quotations.destroy', $q->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin hapus quotation ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data quotation</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-2">
                {{ $quotations->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
