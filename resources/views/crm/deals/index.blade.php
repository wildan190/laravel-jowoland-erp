@extends('layouts.app')

@section('title', 'Deals')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Deals</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Daftar Deals</h4>
        <div class="btn-group">
            <a href="{{ route('deal.create') }}" class="btn btn-warning">
                <i class="fa fa-plus me-1"></i> Tambah Deal
            </a>
            <a href="{{ route('deal.kanban') }}" class="btn btn-secondary">
                <i class="fa fa-columns me-1"></i> View Kanban
            </a>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('deal.index') }}" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control" placeholder="Cari deal, kontak, stage...">
                </div>
                <div class="col-md-3">
                    <select name="stage_id" class="form-select">
                        <option value="">-- Semua Stage --</option>
                        @foreach (\App\Models\PipelineStage::orderBy('order')->get() as $stage)
                            <option value="{{ $stage->id }}" {{ request('stage_id') == $stage->id ? 'selected' : '' }}>
                                {{ $stage->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="contact_id" class="form-select">
                        <option value="">-- Semua Kontak --</option>
                        @foreach (\App\Models\Contact::orderBy('name')->get() as $contact)
                            <option value="{{ $contact->id }}" {{ request('contact_id') == $contact->id ? 'selected' : '' }}>
                                {{ $contact->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Deals --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Title</th>
                        <th>Contact</th>
                        <th>Stage</th>
                        <th>Value</th>
                        <th>Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deals as $deal)
                        <tr>
                            <td>{{ $loop->iteration + ($deals->currentPage() - 1) * $deals->perPage() }}</td>
                            <td>{{ $deal->title }}</td>
                            <td>{{ $deal->contact->name ?? '-' }}</td>
                            <td>{{ $deal->stage->name ?? '-' }}</td>
                            <td>Rp {{ number_format($deal->value, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($deal->status) }}</td>
                            <td>
                                <a href="{{ route('deal.edit', $deal->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('deal.destroy', $deal->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada deal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($deals->hasPages())
            <div class="card-footer">
                {{ $deals->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

{{-- SweetAlert2 Delete Confirm --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus deal ini?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});
</script>
@endsection
