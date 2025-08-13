@extends('layouts.app')

@section('title', 'Manajemen Tim')

@section('content')
<div class="container-fluid">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manajemen Tim</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Daftar Karyawan</h4>
            <a href="{{ route('employees.create') }}" class="btn btn-warning">
                <i class="fa fa-plus me-1"></i> Tambah Karyawan
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Divisi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->phone ?? '-' }}</td>
                                <td>{{ $employee->division->name ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning me-1"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <em>Belum ada data karyawan.</em>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SweetAlert Konfirmasi Hapus --}}
    <script>
        document.querySelectorAll('.delete-form').forEach(form => {
            form.querySelector('button').addEventListener('click', function(e) {
                Swal.fire({
                    title: 'Hapus karyawan ini?',
                    text: "Tindakan ini tidak bisa dibatalkan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Ya, hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
