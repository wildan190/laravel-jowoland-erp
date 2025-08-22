@extends('layouts.app')

@section('title', 'Daftar Pembelian')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Pembelian</li>
        </ol>
    </nav>

    {{-- Filter Form --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('accounting.purchasings.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="name" class="form-control" value="{{ request('name') }}" placeholder="Cari nama barang">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Harga Min</label>
                    <input type="number" name="min_price" class="form-control" value="{{ request('min_price') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Harga Max</label>
                    <input type="number" name="max_price" class="form-control" value="{{ request('max_price') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-warning w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Pembelian</h5>
            <a href="{{ route('accounting.purchasings.create') }}" class="btn btn-warning btn-sm">+ Tambah</a>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchasings as $purchase)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d M Y') }}</td>
                            <td>{{ $purchase->item_name }}</td>
                            <td>Rp{{ number_format($purchase->unit_price, 0, ',', '.') }}</td>
                            <td>{{ $purchase->quantity }}</td>
                            <td>Rp{{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('accounting.purchasings.edit', $purchase) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('accounting.purchasings.destroy', $purchase) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data pembelian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $purchasings->appends(request()->all())->links('pagination::bootstrap-5') }}
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
