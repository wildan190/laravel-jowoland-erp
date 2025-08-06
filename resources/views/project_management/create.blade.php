@extends('layouts.app')

@section('title', 'Tambah Proyek')

@section('content')
    <div class="container">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Daftar Proyek</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Proyek</li>
            </ol>
        </nav>

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-warning"><i class="fa fa-plus-circle me-2"></i> Tambah Proyek</h4>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Proyek</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label fw-semibold">Lokasi</label>
                        <input type="text" name="location" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>

                    <hr>
                    <h5 class="text-dark">Checklist Tahapan Pekerjaan</h5>
                    <div id="task-list">
                        <div class="input-group mb-2">
                            <input type="text" name="tasks[]" class="form-control" placeholder="Nama Tahapan" required>
                            <button type="button" class="btn btn-danger remove-task">×</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-secondary mb-3" id="add-task">+ Tambah Tahapan</button>

                    <div class="mt-3 d-flex justify-content-end">
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Proyek</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        document.getElementById('add-task').addEventListener('click', function() {
            const container = document.createElement('div');
            container.classList.add('input-group', 'mb-2');
            container.innerHTML = `
            <input type="text" name="tasks[]" class="form-control" placeholder="Nama Tahapan" required>
            <button type="button" class="btn btn-danger remove-task">×</button>
        `;
            document.getElementById('task-list').appendChild(container);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-task')) {
                e.target.closest('.input-group').remove();
            }
        });
    </script>
@endsection
