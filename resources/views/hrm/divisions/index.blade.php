@extends('layouts.app')

@section('title', 'Daftar Divisi')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Daftar Divisi</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
            <span><i class="fa fa-sitemap me-1"></i> Daftar Divisi</span>
            <a href="{{ route('divisions.create') }}" class="btn btn-sm btn-warning">
                <i class="fa fa-plus me-1"></i> Tambah Divisi
            </a>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Nama Divisi</th>
                        <th class="text-center" style="width: 20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($divisions as $division)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $division->name }}</td>
                            <td class="text-center">
                                <a href="{{ route('divisions.edit', $division) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="fa fa-edit me-1"></i> Edit
                                </a>

                                <form action="{{ route('divisions.destroy', $division) }}" method="POST"
                                      class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-delete">
                                        <i class="fa fa-trash-alt me-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">Belum ada data divisi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SweetAlert2 Delete Confirmation --}}
<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('.delete-form');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
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
