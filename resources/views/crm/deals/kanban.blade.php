@extends('layouts.app')

@section('title', 'Kanban Deals')

@section('content')
    <div class="container-fluid">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('deal.index') }}">CRM</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kanban Deals</li>
            </ol>
        </nav>

        <h4 class="mb-4 text-dark fw-bold">Pipeline Kanban</h4>

        <div class="row" id="kanban-board">
            @foreach ($stages as $stage)
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="card border-0 shadow-lg h-100 kanban-column">
                        <div class="card-header bg-primary text-white text-center fw-bold text-uppercase py-3">
                            {{ $stage->name }}
                        </div>
                        <div class="card-body bg-light dropzone" data-stage-id="{{ $stage->id }}"
                            style="min-height: 300px; overflow-y: auto;">
                            @if ($stage->deals->isEmpty())
                                <div class="empty-message text-muted text-center py-5">
                                    <i class="bi bi-info-circle me-2"></i>Tidak ada deal
                                </div>
                            @endif

                            @foreach ($stage->deals as $deal)
                                <div class="card mb-3 deal-card shadow-sm border-0" draggable="true"
                                    data-deal-id="{{ $deal->id }}">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong class="text-dark">{{ Str::limit($deal->title, 20) }}</strong>
                                            <i class="bi bi-grip-vertical text-muted" style="cursor: grab;"></i>
                                        </div>
                                        <div class="text-muted mb-2">
                                            <small><i
                                                    class="bi bi-person me-1"></i>{{ $deal->contact->name ?? '-' }}</small>
                                        </div>
                                        <div>
                                            @php
                                                $badgeClass = match ($deal->status) {
                                                    'open' => 'bg-primary',
                                                    'won' => 'bg-success',
                                                    'lost' => 'bg-danger',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp
                                            <span
                                                class="badge {{ $badgeClass }} rounded-pill">{{ Str::ucfirst($deal->status) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .kanban-column {
            transition: all 0.3s ease;
        }

        .kanban-column:hover {
            transform: translateY(-5px);
        }

        .deal-card {
            background-color: white;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: move;
        }

        .deal-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
            transform: scale(1.02);
        }

        .deal-card.dragging {
            opacity: 0.7;
            border: 2px dashed #007bff;
        }

        .dropzone {
            border-radius: 8px;
            padding: 15px;
            background-color: #f8f9fa;
        }

        .dropzone.dragover {
            background-color: #e9ecef;
            border: 2px dashed #007bff;
        }

        .empty-message {
            font-size: 1rem;
            opacity: 0.7;
        }

        @media (max-width: 768px) {
            .kanban-column {
                margin-bottom: 20px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let draggedCard = null;

            document.querySelectorAll('.deal-card').forEach(card => {
                card.addEventListener('dragstart', e => {
                    draggedCard = card;
                    card.classList.add('dragging');
                    setTimeout(() => card.style.display = 'none', 0);
                });

                card.addEventListener('dragend', e => {
                    if (draggedCard) {
                        draggedCard.style.display = 'block';
                        draggedCard.classList.remove('dragging');
                        draggedCard = null;
                    }
                });
            });

            document.querySelectorAll('.dropzone').forEach(container => {
                container.addEventListener('dragover', e => {
                    e.preventDefault();
                    container.classList.add('dragover');
                });

                container.addEventListener('dragleave', e => {
                    container.classList.remove('dragover');
                });

                container.addEventListener('drop', function(e) {
                    e.preventDefault();
                    container.classList.remove('dragover');

                    if (draggedCard) {
                        const oldContainer = draggedCard.parentElement;
                        this.appendChild(draggedCard);

                        // Remove "Tidak ada deal" from new container
                        this.querySelector('.empty-message')?.remove();

                        // Add "Tidak ada deal" if old container is now empty
                        if (oldContainer.querySelectorAll('.deal-card').length === 0) {
                            const emptyMsg = document.createElement('div');
                            emptyMsg.classList.add('empty-message', 'text-muted', 'text-center',
                                'py-5');
                            emptyMsg.innerHTML =
                                '<i class="bi bi-info-circle me-2"></i>Tidak ada deal';
                            oldContainer.appendChild(emptyMsg);
                        }

                        const dealId = draggedCard.dataset.dealId;
                        const newStageId = this.dataset.stageId;

                        fetch("{{ url('/crm/deal') }}/" + dealId + "/move", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    stage_id: newStageId
                                })
                            })
                            .then(res => {
                                if (!res.ok) throw new Error('Gagal menyimpan');
                                return res.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: 'Deal berhasil dipindahkan.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: 'Data tidak dapat diproses.'
                                    });
                                }
                            })
                            .catch(err => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Terjadi kesalahan saat memindahkan.'
                                });
                            });
                    }
                });
            });
        });
    </script>
@endsection
