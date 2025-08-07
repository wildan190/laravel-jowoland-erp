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

                    {{-- Nama Proyek --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Proyek</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Lokasi --}}
                    <div class="mb-3">
                        <label for="location" class="form-label">Lokasi</label>
                        <input type="text" name="location" id="location"
                            class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}"
                            required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Mulai --}}
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date"
                            class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}"
                            required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Selesai --}}
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date"
                            class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}"
                            required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kontak --}}
                    <div class="mb-4">
                        <label for="contact_id" class="form-label">Pilih Kontak</label>
                        <select name="contact_id" id="contact_id"
                            class="form-select @error('contact_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kontak --</option>
                            @foreach ($contacts as $contact)
                                <option value="{{ $contact->id }}"
                                    {{ old('contact_id') == $contact->id ? 'selected' : '' }}>
                                    {{ $contact->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('contact_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-warning"><i class="fa fa-save me-1"></i> Simpan Proyek</button>
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
