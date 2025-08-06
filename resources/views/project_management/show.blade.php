@extends('layouts.app')

@section('title', 'Detail Proyek')

@section('content')
<div class="container">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Daftar Proyek</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $project->name }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 text-warning"><i class="fa fa-folder-open me-2"></i> Detail Proyek</h4>
        <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-warning">
            <i class="fa fa-edit me-1"></i> Edit Proyek
        </a>
    </div>

    {{-- Card Detail --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="text-dark">{{ $project->name }}</h5>
            <p><strong>Lokasi:</strong> {{ $project->location }}</p>
            <p><strong>Deskripsi:</strong> {{ $project->description }}</p>

            {{-- Progress --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Progress</label>
                <div class="progress" style="height: 24px;">
                    <div id="progress-bar" class="progress-bar" role="progressbar" style="width: {{ $project->progress_percentage }}%" aria-valuenow="{{ $project->progress_percentage }}" aria-valuemin="0" aria-valuemax="100">
                        <span id="progress-text">{{ $project->progress_percentage }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pipeline Tahapan (Checklist Style) --}}
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white">
            <i class="fa fa-check-circle me-1"></i> Pipeline Tahapan Proyek
        </div>
        <div class="card-body">
            <div class="pipeline-steps d-flex flex-nowrap overflow-auto">
                @foreach ($project->tasks as $task)
                    <div class="step-item text-center flex-shrink-0 me-3" style="width: 180px;">
                        <div class="step-box p-3 rounded shadow-sm {{ $task->is_done ? 'bg-success text-white' : 'bg-light' }}">
                            <div class="mb-2">
                                <input type="checkbox" data-task-id="{{ $task->id }}" class="task-check form-check-input" {{ $task->is_done ? 'checked' : '' }}>
                            </div>
                            <div class="step-name fw-semibold">{{ $task->task_name }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Style --}}
<style>
    .step-item .step-box {
        transition: all 0.2s ease;
        border: 1px solid #ddd;
        min-height: 100px;
    }
    .step-box.bg-success {
        background-color: #198754 !important;
        color: white;
    }
</style>

{{-- JavaScript --}}
<script>
document.querySelectorAll('.task-check').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const taskId = this.dataset.taskId;
        const url = `{{ route('tasks.updateStatus', ':id') }}`.replace(':id', taskId);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ is_done: this.checked })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.updated_percentage !== undefined) {
                const bar = document.getElementById('progress-bar');
                const text = document.getElementById('progress-text');

                bar.style.width = data.updated_percentage + '%';
                bar.setAttribute('aria-valuenow', data.updated_percentage);
                text.textContent = data.updated_percentage + '%';

                // Update style visual untuk checkbox
                if (this.checked) {
                    this.closest('.step-box').classList.add('bg-success', 'text-white');
                } else {
                    this.closest('.step-box').classList.remove('bg-success', 'text-white');
                }

                // Ubah warna progress bar sesuai % 
                if (data.updated_percentage >= 80) {
                    bar.className = 'progress-bar bg-success';
                } else if (data.updated_percentage >= 40) {
                    bar.className = 'progress-bar bg-warning';
                } else {
                    bar.className = 'progress-bar bg-danger';
                }
            }
        });
    });
});
</script>
@endsection
