@extends('layouts.app')

@section('title', 'Kanban Deals')

@section('content')
    <div class="container-fluid">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('deal.index') }}">CRM</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kanban Deals</li>
            </ol>
        </nav>

        <h4 class="mb-4">Pipeline Kanban</h4>

        <div class="row" id="kanban-board">
            @foreach ($stages as $stage)
                <div class="col-md-3">
                    <div class="card border-dark mb-3 shadow-sm">
                        <div class="card-header bg-dark text-white text-center fw-bold">
                            {{ $stage->name }}
                        </div>
                        <div class="card-body bg-light min-vh-25 dropzone" data-stage-id="{{ $stage->id }}" style="min-height: 200px;">
                            @if ($stage->deals->isEmpty())
                                <div class="empty-message text-muted text-center">Tidak ada deal</div>
                            @endif

                            @foreach ($stage->deals as $deal)
                                <div class="card mb-2 deal-card shadow-sm" draggable="true" data-deal-id="{{ $deal->id }}">
                                    <div class="card-body p-2">
                                        <strong>{{ $deal->title }}</strong>
                                        <div><small class="text-muted">{{ $deal->contact->name ?? '-' }}</small></div>
                                        <div>
                                            @php
                                                $badgeClass = match($deal->status) {
                                                    'open' => 'bg-primary',
                                                    'won' => 'bg-success',
                                                    'lost' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $deal->status }}</span>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let draggedCard = null;

            document.querySelectorAll('.deal-card').forEach(card => {
                card.addEventListener('dragstart', e => {
                    draggedCard = card;
                    setTimeout(() => card.style.display = 'none', 0);
                });

                card.addEventListener('dragend', e => {
                    if (draggedCard) {
                        draggedCard.style.display = 'block';
                        draggedCard = null;
                    }
                });
            });

            document.querySelectorAll('.dropzone').forEach(container => {
                container.addEventListener('dragover', e => e.preventDefault());

                container.addEventListener('drop', function (e) {
                    e.preventDefault();

                    if (draggedCard) {
                        const oldContainer = draggedCard.parentElement;

                        this.appendChild(draggedCard);

                        // Remove "Tidak ada deal" from new container
                        this.querySelector('.empty-message')?.remove();

                        // Add "Tidak ada deal" if old container is now empty
                        if (oldContainer.querySelectorAll('.deal-card').length === 0) {
                            const emptyMsg = document.createElement('div');
                            emptyMsg.classList.add('empty-message', 'text-muted', 'text-center');
                            emptyMsg.innerText = 'Tidak ada deal';
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
                            body: JSON.stringify({ stage_id: newStageId })
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Gagal menyimpan');
                            return res.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Berhasil', 'Deal berhasil dipindahkan.', 'success');
                            } else {
                                Swal.fire('Gagal', 'Data tidak dapat diproses.', 'error');
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error', 'Terjadi kesalahan saat memindahkan.', 'error');
                        });
                    }
                });
            });
        });
    </script>
@endsection
