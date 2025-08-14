@extends('layouts.app')

@section('title', 'Detail Proyek')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Daftar Proyek</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $project->name }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-dark fw-bold"><i class="fas fa-folder-open me-2 text-warning"></i> {{ $project->name }}</h2>
        <div>
            <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning btn-sm shadow-sm me-2">
                <i class="fas fa-edit me-1"></i> Edit Proyek
            </a>
            {{-- <button class="btn btn-outline-secondary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#shareModal">
                <i class="fas fa-share-alt me-1"></i> Bagikan
            </button> --}}
        </div>
    </div>

    {{-- Card Detail --}}
    <div class="card shadow-sm mb-4 border-0 rounded-3">
        <div class="card-body p-4">
            {{-- Informasi Perusahaan / Contact --}}
            @if ($project->contact)
                <div class="mb-4 pb-3 border-bottom">
                    <h4 class="text-uppercase fw-bold text-primary mb-3">{{ $project->contact->company }}</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Klien:</strong> {{ $project->contact->name }}</p>
                            <p class="mb-2"><strong>Email:</strong> <a href="mailto:{{ $project->contact->email }}" class="text-decoration-none">{{ $project->contact->email }}</a></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Telepon:</strong> <a href="tel:{{ $project->contact->phone }}" class="text-decoration-none">{{ $project->contact->phone }}</a></p>
                            @if ($project->contact->address)
                                <p class="mb-2"><strong>Alamat:</strong> {{ $project->contact->address }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Informasi Proyek --}}
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <h5 class="fw-bold text-dark mb-3">Informasi Proyek</h5>
                    <p class="mb-2"><strong>Lokasi:</strong> {{ $project->location }}</p>
                    <p class="mb-2"><strong>Deskripsi:</strong> {{ $project->description }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><strong>Tanggal Mulai:</strong> {{ \Carbon\Carbon::parse($project->start_date)->format('d M Y') }}</p>
                    <p class="mb-2"><strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($project->end_date)->format('d M Y') }}</p>
                </div>
            </div>

            {{-- Status Proyek --}}
            <div class="alert alert-{{ $project->is_overdue ? 'danger' : ($project->progress_percentage == 100 ? 'success' : 'info') }} d-flex align-items-center rounded-pill p-3 shadow-sm">
                <i class="fas {{ $project->is_overdue ? 'fa-exclamation-circle' : ($project->progress_percentage == 100 ? 'fa-check-circle' : 'fa-hourglass-half') }} me-2"></i>
                <span>
                    @if ($project->is_overdue)
                        Proyek ini telah melewati tanggal target dan belum selesai (Overdue).
                    @elseif ($project->progress_percentage == 100)
                        Proyek telah selesai tepat waktu.
                    @else
                        Proyek masih berjalan sesuai jadwal.
                    @endif
                </span>
            </div>

            {{-- Progress --}}
            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Progress Proyek</label>
                <div class="progress rounded-pill shadow-sm" style="height: 30px;">
                    <div id="progress-bar" class="progress-bar {{ $project->progress_percentage >= 80 ? 'bg-success' : ($project->progress_percentage >= 40 ? 'bg-warning' : 'bg-danger') }} text-white fw-bold" role="progressbar"
                        style="width: {{ $project->progress_percentage }}%; transition: width 0.3s ease;"
                        aria-valuenow="{{ $project->progress_percentage }}" aria-valuemin="0" aria-valuemax="100">
                        <span id="progress-text">{{ $project->progress_percentage }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pipeline Tahapan (Checklist Style) --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-gradient-warning text-white rounded-top-3">
            <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i> Pipeline Tahapan Proyek</h5>
        </div>
        <div class="card-body p-4">
            <div class="pipeline-steps d-flex flex-nowrap overflow-auto pb-3">
                @foreach ($project->tasks as $task)
                    <div class="step-item text-center flex-shrink-0 me-4" style="width: 200px;">
                        <div class="step-box p-4 rounded-3 shadow-sm {{ $task->is_done ? 'bg-success text-white' : 'bg-light' }} position-relative"
                             data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $task->task_name }}">
                            <div class="mb-3">
                                <input type="checkbox" data-task-id="{{ $task->id }}"
                                       class="task-check form-check-input large-checkbox"
                                       {{ $task->is_done ? 'checked' : '' }}>
                            </div>
                            <div class="step-name fw-semibold text-truncate">{{ $task->task_name }}</div>
                            <div class="small mt-2 text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                            </div>
                            @if ($task->is_done)
                                <span class="position-absolute top-0 end-0 p-2 text-success">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Share Modal --}}
    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow-lg">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="shareModalLabel"><i class="fas fa-share-alt me-2"></i> Bagikan Proyek</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Bagikan proyek ini melalui:</p>
                    <div class="d-flex justify-content-around">
                        <a href="#" class="btn btn-outline-primary rounded-circle p-3" title="Email">
                            <i class="fas fa-envelope fa-lg"></i>
                        </a>
                        <a href="#" class="btn btn-outline-success rounded-circle p-3" title="WhatsApp">
                            <i class="fab fa-whatsapp fa-lg"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info rounded-circle p-3" title="Copy Link">
                            <i class="fas fa-link fa-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Style --}}
<style>

    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .step-box {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        min-height: 140px;
        cursor: pointer;
    }

    .step-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .large-checkbox {
        transform: scale(1.8);
        cursor: pointer;
    }

    .bg-gradient-warning {
        background: linear-gradient(45deg, #ffc107, #ffca2c);
    }

    .progress-bar {
        transition: width 0.5s ease, background-color 0.5s ease;
    }

    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .modal-content {
        border-radius: 1rem !important;
    }

    .btn-outline-primary:hover, .btn-outline-success:hover, .btn-outline-info:hover {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }
</style>

{{-- JavaScript --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Bootstrap tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        // Task checkbox handler
        document.querySelectorAll('.task-check').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const taskId = this.dataset.taskId;
                const url = `{{ route('tasks.updateStatus', ':id') }}`.replace(':id', taskId);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        is_done: this.checked
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.updated_percentage !== undefined) {
                        const bar = document.getElementById('progress-bar');
                        const text = document.getElementById('progress-text');
                        const stepBox = this.closest('.step-box');

                        // Update progress bar
                        bar.style.width = data.updated_percentage + '%';
                        bar.setAttribute('aria-valuenow', data.updated_percentage);
                        text.textContent = data.updated_percentage + '%';

                        // Update task status
                        if (this.checked) {
                            stepBox.classList.add('bg-success', 'text-white');
                            const checkIcon = document.createElement('span');
                            checkIcon.className = 'position-absolute top-0 end-0 p-2 text-success';
                            checkIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
                            stepBox.appendChild(checkIcon);
                        } else {
                            stepBox.classList.remove('bg-success', 'text-white');
                            const checkIcon = stepBox.querySelector('.position-absolute');
                            if (checkIcon) checkIcon.remove();
                        }

                        // Update progress bar color
                        bar.className = 'progress-bar text-white fw-bold ' +
                            (データを更新_percentage >= 80 ? 'bg-success' : (data.updated_percentage >= 40 ? 'bg-warning' : 'bg-danger'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memperbarui status tugas.');
                    this.checked = !this.checked; // Revert checkbox state
                });
            });
        });
    });
</script>
@endsection