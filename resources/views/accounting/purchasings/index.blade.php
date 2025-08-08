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
                        @foreach ($purchasings as $purchase)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d M Y') }}</td>
                                <td>{{ $purchase->item_name }}</td>
                                <td>Rp{{ number_format($purchase->unit_price, 0, ',', '.') }}</td>
                                <td>{{ $purchase->quantity }}</td>
                                <td>Rp{{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('accounting.purchasings.edit', $purchase) }}"
                                        class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('accounting.purchasings.destroy', $purchase) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-btn"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
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
