@extends('layouts.app')

@section('title', 'Buat Ads Plan')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('crm.ads_plans.index') }}">Ads Plans</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat Ads Plan</li>
        </ol>
    </nav>

    <h4 class="mb-3">Buat Ads Plan Baru</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('crm.ads_plans.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Campaign</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Objective</label>
                    <select name="objective" class="form-select" required>
                        <option value="">-- Pilih Objective --</option>
                        <option value="Awareness">Awareness</option>
                        <option value="Engagement">Engagement</option>
                        <option value="Leads">Leads</option>
                        <option value="Sales">Sales</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Audience</label>
                    <textarea name="audience" rows="3" class="form-control" placeholder="Contoh: Wanita 20-35 tahun, lokasi Jakarta, minat fashion"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Budget (Rp)</label>
                    <input type="number" class="form-control" name="budget" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="start_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal Selesai</label>
                        <input type="date" class="form-control" name="end_date" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Platform</label>
                    <select name="platform" class="form-select" required>
                        <option value="Meta Ads">Meta Ads</option>
                        <option value="Google Ads">Google Ads</option>
                        <option value="TikTok Ads">TikTok Ads</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Catatan</label>
                    <textarea name="notes" rows="3" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-warning">
                    <i class="fa fa-save me-1"></i> Simpan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
