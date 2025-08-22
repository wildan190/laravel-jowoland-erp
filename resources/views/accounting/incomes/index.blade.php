@extends('layouts.app')

@section('title', 'Daftar Pemasukan')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Pemasukan</li>
        </ol>
    </nav>

    {{-- Filter Tanggal --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('accounting.incomes.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" class="form-control"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" class="form-control"
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('accounting.incomes.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-sync me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Pemasukan --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Pemasukan</h5>
            <a href="{{ route('accounting.incomes.create') }}" class="btn btn-warning btn-sm">+ Tambah</a>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Client</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($incomes as $income)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($income->date)->format('d M Y') }}</td>
                            <td>{{ $income->deal->contact->name ?? ($income->contact->name ?? '-') }}</td>
                            <td>{{ $income->description }}</td>
                            <td class="text-end">Rp{{ number_format($income->amount, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('accounting.incomes.edit', $income) }}"
                                   class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('accounting.incomes.destroy', $income) }}" method="POST"
                                      class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger delete-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data pemasukan</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total</th>
                        <th colspan="2" class="text-start">
                            Rp{{ number_format($incomes->sum('amount'), 0, ',', '.') }}
                        </th>
                    </tr>
                </tfoot>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $incomes->links('pagination::bootstrap-5') }}
            </div>
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
            text: "Data yang dihapus tidak dapat dikembalikan.",
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
