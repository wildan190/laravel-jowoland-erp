@extends('layouts.app')

@section('title', 'Daftar Proyek')

@section('content')
    <div class="container">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Proyek</li>
            </ol>
        </nav>

        {{-- Title & Button --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Daftar Proyek</h4>
            <a href="{{ route('projects.create') }}" class="btn btn-warning">
                <i class="fa fa-plus me-1"></i> Tambah Proyek
            </a>
        </div>

        {{-- Table --}}
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Progress</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $project)
                            <tr>
                                <td>{{ $project->name }}</td>
                                <td style="min-width: 200px;">
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 18px;">
                                            <div class="progress-bar 
                                            {{ $project->progress_percentage < 40
                                                ? 'bg-danger'
                                                : ($project->progress_percentage < 70
                                                    ? 'bg-warning'
                                                    : 'bg-success') }}"
                                                role="progressbar" style="width: {{ $project->progress_percentage }}%;">
                                                {{ $project->progress_percentage }}%
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center" style="white-space: nowrap;">
                                    <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-info me-1">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-primary me-1">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada proyek.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation --}}
    <script>
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus proyek ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Ya, hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endsection
