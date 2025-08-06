@extends('layouts.app')

@section('title', 'Daftar Proyek')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Project Management</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between mb-3">
            <h4>Daftar Proyek</h4>
            <a href="{{ route('projects.create') }}" class="btn btn-warning"><i class="fa fa-plus me-1"></i> Tambah Proyek</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Proyek</th>
                            <th>Lokasi</th>
                            <th>Progress</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $project)
                            <tr>
                                <td>{{ $project->name }}</td>
                                <td>{{ $project->location }}</td>
                                <td>
                                    @php
                                        $progress = $project->progresses->avg('percentage') ?? 0;
                                    @endphp
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ round($progress) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('projects.progress.index', $project) }}" class="btn btn-sm btn-success">Progress</a>
                                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline delete-form">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">Belum ada proyek.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: true
            });
        </script>
    @endif
@endsection
