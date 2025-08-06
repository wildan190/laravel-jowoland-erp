@extends('layouts.app')

@section('title', 'Progress Proyek')

@section('content')
    <div class="container">
    
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Project Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">Progress: {{ $project->name }}</li>
            </ol>
        </nav>

        <h4 class="mb-3">Progress: {{ $project->name }}</h4>

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('projects.progress.store', $project) }}" method="POST">
                    @csrf

                    <div class="row g-2">
                        <div class="col-md-4">
                            <label for="stage" class="form-label">Tahapan</label>
                            <input type="text" name="stage" id="stage" class="form-control" required>
                        </div>

                        <div class="col-md-2">
                            <label for="percentage" class="form-label">Progress (%)</label>
                            <input type="number" name="percentage" id="percentage" class="form-control" min="0"
                                max="100" required>
                        </div>

                        <div class="col-md-4">
                            <label for="note" class="form-label">Catatan</label>
                            <input type="text" name="note" id="note" class="form-control">
                        </div>

                        <div class="col-md-2 d-grid">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-success">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tahapan</th>
                            <th>Progress</th>
                            <th>Catatan</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($progresses as $progress)
                            <tr>
                                <td>{{ $progress->stage }}</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ $progress->percentage }}%;">
                                            {{ $progress->percentage }}%
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $progress->note }}</td>
                                <td>{{ $progress->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada progress.</td>
                            </tr>
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
                showConfirmButton: false
            });
        </script>
    @endif
@endsection
