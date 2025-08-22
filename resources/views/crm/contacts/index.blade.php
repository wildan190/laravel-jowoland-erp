@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kontak</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between mb-3">
            <h4>Daftar Kontak</h4>
            <a href="{{ route('contacts.create') }}" class="btn btn-warning">
                <i class="fas fa-plus"></i> Tambah Kontak
            </a>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('contacts.index') }}" class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari nama, email, telepon...">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="company" value="{{ request('company') }}" class="form-control"
                            placeholder="Filter perusahaan">
                    </div>
                    <div class="col-md-2">
                        <select name="has_email" class="form-select">
                            <option value="">-- Email --</option>
                            <option value="1" {{ request('has_email') == '1' ? 'selected' : '' }}>Hanya yang ada
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="has_phone" class="form-select">
                            <option value="">-- Telepon --</option>
                            <option value="1" {{ request('has_phone') == '1' ? 'selected' : '' }}>Hanya yang ada
                            </option>
                        </select>
                    </div>
                    <div class="col-md-1 d-grid">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>


        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Perusahaan</th>
                                <th>Alamat</th>
                                <th>Catatan</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contacts as $contact)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td>{{ $contact->phone ?? '-' }}</td>
                                    <td>{{ $contact->company ?? '-' }}</td>
                                    <td>{{ $contact->address ?? '-' }}</td>
                                    <td>{{ $contact->notes ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('contacts.destroy', $contact) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada kontak.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $contacts->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data kontak akan dihapus permanen!",
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
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

@endsection
