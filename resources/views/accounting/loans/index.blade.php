@extends('layouts.app')

@section('title', 'Daftar Kredit / Hutang')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Kredit / Hutang</li>
        </ol>
    </nav>

    {{-- Filter --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('accounting.loans.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="vendor" class="form-label">Vendor / Bank</label>
                    <input type="text" class="form-control" name="vendor" id="vendor" 
                           value="{{ request('vendor') }}" placeholder="Cari vendor/bank">
                </div>
                {{-- <div class="col-md-3">
                    <label for="due_date_start" class="form-label">Tanggal Jatuh Tempo (Mulai)</label>
                    <input type="date" class="form-control" name="due_date_start" id="due_date_start" 
                           value="{{ request('due_date_start') }}">
                </div>
                <div class="col-md-3">
                    <label for="due_date_end" class="form-label">Tanggal Jatuh Tempo (Sampai)</label>
                    <input type="date" class="form-control" name="due_date_end" id="due_date_end" 
                           value="{{ request('due_date_end') }}">
                </div> --}}
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-warning mt-2">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('accounting.loans.index') }}" class="btn btn-secondary mt-2">
                        <i class="fas fa-times me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Hutang</h5>
            <a href="{{ route('accounting.loans.create') }}" class="btn btn-warning btn-sm">+ Tambah Hutang</a>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped mb-0 table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Vendor / Bank</th>
                        <th>Pokok</th>
                        <th>Bunga (%)</th>
                        <th>Total Hutang</th>
                        <th>Cicilan/Bulan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Akhir</th>
                        <th>Lama Cicilan</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $loan->vendor }}</td>
                            <td>Rp{{ number_format($loan->principal, 0, ',', '.') }}</td>
                            <td>{{ $loan->interest_rate }}</td>
                            <td>Rp{{ number_format($loan->total_debt, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($loan->monthly_installment, 0, ',', '.') }}</td>
                            <td>{{ $loan->start_date ? $loan->start_date->format('d M Y') : '-' }}</td>
                            <td>{{ $loan->end_date ? $loan->end_date->format('d M Y') : '-' }}</td>
                            <td>{{ $loan->installments }} bulan</td>
                            <td>{{ $loan->description ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('accounting.loans.edit', $loan) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('accounting.loans.destroy', $loan) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">Tidak ada data hutang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SweetAlert --}}
<script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: 'Yakin?',
                text: "Data hutang yang dihapus tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, hapus'
            }).then((res) => {
                if (res.isConfirmed) form.submit();
            });
        });
    });
</script>
@endsection
