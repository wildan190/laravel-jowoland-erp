@extends('layouts.app')

@section('title', 'Penggajian')

@section('content')
<div class="container">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Penggajian</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
            <span><i class="fa fa-money-bill-wave me-1"></i> Data Penggajian</span>
            <a href="{{ route('payrolls.create') }}" class="btn btn-sm btn-warning">
                <i class="fa fa-plus me-1"></i> Tambah Penggajian
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama Karyawan</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payrolls as $payroll)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $payroll->employee?->name ?? '-' }}</td>
                                <td>Rp{{ number_format($payroll->employee?->salary ?? 0, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($payroll->allowance ?? 0, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format(($payroll->employee?->salary ?? 0) + ($payroll->allowance ?? 0), 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($payroll->pay_date)->format('d M Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('payrolls.edit', $payroll) }}" class="btn btn-sm btn-primary me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('payrolls.destroy', $payroll) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data penggajian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert --}}
<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: 'Yakin ingin menghapus data ini?',
                text: "Data yang dihapus tidak bisa dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
