@extends('layouts.app')

@section('title', 'Tambah Proyek')

@section('content')
    <div class="container-fluid">
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

                    {{-- Nama Proyek --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Proyek</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    {{-- Pilih Kontak / Perusahaan --}}
                    <div class="mb-3">
                        <label for="contact_id" class="form-label fw-semibold">Perusahaan / Kontak</label>
                        <select name="contact_id" class="form-select" required>
                            <option value="">-- Pilih Kontak --</option>
                            @foreach ($contacts as $contact)
                                <option value="{{ $contact->id }}">{{ $contact->company }} - {{ $contact->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Lokasi --}}
                    <div class="mb-3">
                        <label for="location" class="form-label fw-semibold">Lokasi</label>
                        <input type="text" name="location" class="form-control">
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    {{-- Tanggal Mulai dan Selesai --}}
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

                    {{-- Checklist Tahapan --}}
                    <h5 class="text-dark">Checklist Tahapan Pekerjaan</h5>
                    <div id="task-list">
                        <div class="input-group mb-2">
                            <input type="text" name="tasks[]" class="form-control" placeholder="Nama Tahapan" required>
                            <input type="date" name="tasks_due_date[]" class="form-control" placeholder="Due Date">
                            <button type="button" class="btn btn-danger remove-task">×</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-secondary mb-3" id="add-task">+ Tambah Tahapan</button>

                    {{-- Tombol Simpan --}}
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
        const addTaskBtn = document.getElementById('add-task');
        const taskList = document.getElementById('task-list');
        const form = document.querySelector('form');

        // Menambah input tahapan baru
        addTaskBtn.addEventListener('click', () => {
            const container = document.createElement('div');
            container.classList.add('input-group', 'mb-2');
            container.innerHTML = `
            <input type="text" name="tasks[]" class="form-control task-name" placeholder="Nama Tahapan" required>
            <input type="date" name="tasks_due_date[]" class="form-control" placeholder="Due Date">
            <button type="button" class="btn btn-danger remove-task">×</button>
        `;
            taskList.appendChild(container);
        });

        // Hapus input tahapan
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-task')) {
                e.target.closest('.input-group').remove();
            }
        });

        // Cek duplikat saat submit form
        form.addEventListener('submit', function(e) {
            const taskInputs = document.querySelectorAll('.task-name');
            const taskNames = [];

            for (let input of taskInputs) {
                const name = input.value.trim().toLowerCase();

                if (taskNames.includes(name)) {
                    e.preventDefault();
                    alert(`Tahapan "${input.value}" sudah dimasukkan lebih dari sekali.`);
                    input.focus();
                    return false;
                }

                taskNames.push(name);
            }
        });
    </script>

@endsection
