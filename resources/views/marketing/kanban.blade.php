@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- Breadcrumb (unchanged) --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                        style="color: rgb(0, 0, 0); text-decoration: none;">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('marketing.kanban') }}"
                        style="color: rgb(0, 0, 0); text-decoration: none;">Kanban Boards</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: rgb(255, 96, 0); font-weight: 600;">
                    Manage Boards</li>
            </ol>
        </nav>

        <div class="card shadow-sm border-0" style="background-color: #fff;">
            <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                <span><i class="fa fa-columns me-1"></i> Kanban Board Management</span>
                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                    data-bs-target="#createBoardModal">
                    <i class="fa fa-plus me-1"></i> Create New Board
                </button>
            </div>
            <div class="card-body p-4">
                @if (session('status'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: '{{ session('status') }}',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        });
                    </script>
                @endif

                {{-- Modal for creating a new Kanban board (unchanged) --}}
                <div class="modal fade" id="createBoardModal" tabindex="-1" aria-labelledby="createBoardModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" style="background-color: #fff;">
                            <div class="modal-header bg-white fw-bold">
                                <h5 class="modal-title" id="createBoardModalLabel"><i class="fa fa-plus me-1"></i> Create
                                    New Board</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('marketing.kanban') }}" method="POST" class="row">
                                    @csrf
                                    <div>
                                        <div class="col-12">
                                            <label for="name" class="form-label fw-semibold">Board Name</label>
                                            <input type="text" name="name" id="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="description" class="form-label fw-semibold">Description</label>
                                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                                rows="4">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-warning w-100"><i
                                                    class="fa fa-plus me-1"></i>
                                                Create Board</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kanban Boards --}}
                @foreach ($boards as $board)
                    <div class="mb-5">
                        <div class="mb-3">
                            <h2 class="h5" style="color: rgb(0, 0, 0);">{{ $board->name }}</h2>
                            <p class="text-muted">{{ $board->description ?: 'No description provided' }}</p>
                        </div>

                        {{-- Kanban Columns in one row --}}
                        <div class="row kanban-columns" data-board-id="{{ $board->id }}">
                            {{-- Todo Column --}}
                            <div class="col d-flex">
                                <div class="kanban-column todo p-3 rounded shadow-sm flex-fill" data-status="todo"
                                    style="background-color: #f8f9fa; min-height: 300px;">
                                    <h3 class="h6 mb-3" style="color: rgb(0, 0, 0);">Todo</h3>
                                    <div class="mb-3">
                                        <button class="btn btn-sm btn-warning w-100" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#taskFormTodo{{ $board->id }}"
                                            aria-expanded="false" aria-controls="taskFormTodo{{ $board->id }}">
                                            <i class="fa fa-plus me-1"></i> Add Task
                                        </button>
                                        <div class="collapse mt-3" id="taskFormTodo{{ $board->id }}">
                                            <div class="card card-body border-0 shadow-sm" style="background-color: #fff;">
                                                <form action="{{ route('marketing.kanban.task.store') }}" method="POST"
                                                    class="row align-items-end">
                                                    @csrf
                                                    <input type="hidden" name="kanban_board_id"
                                                        value="{{ $board->id }}">
                                                    <input type="hidden" name="status" value="todo">
                                                    <div class="col-12">
                                                        <label for="task-title-todo-{{ $board->id }}"
                                                            class="form-label fw-semibold">Task Title</label>
                                                        <input type="text" name="title"
                                                            id="task-title-todo-{{ $board->id }}"
                                                            class="form-control @error('title') is-invalid @enderror"
                                                            value="{{ old('title') }}" required>
                                                        @error('title')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="task-description-todo-{{ $board->id }}"
                                                            class="form-label fw-semibold">Task Description</label>
                                                        <textarea name="description" id="task-description-todo-{{ $board->id }}"
                                                            class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                                                        @error('description')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-12 mt-3">
                                                            <button type="submit" class="btn btn-warning w-100"><i
                                                                    class="fa fa-plus me-1"></i> Add Task</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach ($board->tasks->where('status', 'todo') as $task)
                                        <div class="kanban-task card mb-2 shadow-sm" data-task-id="{{ $task->id }}"
                                            style="background-color: #fff; border: 1px solid #ddd;">
                                            <div class="card-body p-3">
                                                <h5 class="card-title mb-2" style="color: rgb(0, 0, 0);">
                                                    {{ $task->title }}</h5>
                                                <p class="card-text text-muted">
                                                    {{ $task->description ?: 'No description' }}</p>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('marketing.kanban.task.edit', $task) }}"
                                                        class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('marketing.kanban.task.destroy', $task) }}"
                                                        method="POST" style="display:inline;" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                            title="Delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            {{-- Doing Column --}}
                            <div class="col-md-4 d-flex">
                                <div class="kanban-column doing p-3 rounded shadow-sm flex-fill" data-status="doing"
                                    style="background-color: #f8f9fa; min-height: 300px;">
                                    <h3 class="h6 mb-3" style="color: rgb(0, 0, 0);">Doing</h3>
                                    <div class="mb-3">
                                        <button class="btn btn-sm btn-warning w-100" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#taskFormDoing{{ $board->id }}"
                                            aria-expanded="false" aria-controls="taskFormDoing{{ $board->id }}">
                                            <i class="fa fa-plus me-1"></i> Add Task
                                        </button>
                                        <div class="collapse mt-3" id="taskFormDoing{{ $board->id }}">
                                            <div class="card card-body border-0 shadow-sm"
                                                style="background-color: #fff;">
                                                <form action="{{ route('marketing.kanban.task.store') }}" method="POST"
                                                    class="row align-items-end">
                                                    @csrf
                                                    <input type="hidden" name="kanban_board_id"
                                                        value="{{ $board->id }}">
                                                    <input type="hidden" name="status" value="doing">
                                                    <div class="col-12">
                                                        <label for="task-title-doing-{{ $board->id }}"
                                                            class="form-label fw-semibold">Task Title</label>
                                                        <input type="text" name="title"
                                                            id="task-title-doing-{{ $board->id }}"
                                                            class="form-control @error('title') is-invalid @enderror"
                                                            value="{{ old('title') }}" required>
                                                        @error('title')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="task-description-doing-{{ $board->id }}"
                                                            class="form-label fw-semibold">Task Description</label>
                                                        <textarea name="description" id="task-description-doing-{{ $board->id }}"
                                                            class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                                                        @error('description')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                     <div class="col-12 mt-3">
                                                        <button type="submit" class="btn btn-warning w-100"><i
                                                                class="fa fa-plus me-1"></i> Add Task</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach ($board->tasks->where('status', 'doing') as $task)
                                        <div class="kanban-task card mb-2 shadow-sm" data-task-id="{{ $task->id }}"
                                            style="background-color: #fff; border: 1px solid #ddd;">
                                            <div class="card-body p-3">
                                                <h5 class="card-title mb-2" style="color: rgb(0, 0, 0);">
                                                    {{ $task->title }}</h5>
                                                <p class="card-text text-muted">
                                                    {{ $task->description ?: 'No description' }}</p>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('marketing.kanban.task.edit', $task) }}"
                                                        class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('marketing.kanban.task.destroy', $task) }}"
                                                        method="POST" style="display:inline;" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                            title="Delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            {{-- Done Column --}}
                            <div class="col-md-4 d-flex">
                                <div class="kanban-column done p-3 rounded shadow-sm flex-fill" data-status="done"
                                    style="background-color: #f8f9fa; min-height: 300px;">
                                    <h3 class="h6 mb-3" style="color: rgb(0, 0, 0);">Done</h3>
                                    <div class="mb-3">
                                        <button class="btn btn-sm btn-warning w-100" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#taskFormDone{{ $board->id }}"
                                            aria-expanded="false" aria-controls="taskFormDone{{ $board->id }}">
                                            <i class="fa fa-plus me-1"></i> Add Task
                                        </button>
                                        <div class="collapse mt-3" id="taskFormDone{{ $board->id }}">
                                            <div class="card card-body border-0 shadow-sm"
                                                style="background-color: #fff;">
                                                <form action="{{ route('marketing.kanban.task.store') }}" method="POST"
                                                    class="row align-items-end">
                                                    @csrf
                                                    <input type="hidden" name="kanban_board_id"
                                                        value="{{ $board->id }}">
                                                    <input type="hidden" name="status" value="done">
                                                    <div class="col-12">
                                                        <label for="task-title-done-{{ $board->id }}"
                                                            class="form-label fw-semibold">Task Title</label>
                                                        <input type="text" name="title"
                                                            id="task-title-done-{{ $board->id }}"
                                                            class="form-control @error('title') is-invalid @enderror"
                                                            value="{{ old('title') }}" required>
                                                        @error('title')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="task-description-done-{{ $board->id }}"
                                                            class="form-label fw-semibold">Task Description</label>
                                                        <textarea name="description" id="task-description-done-{{ $board->id }}"
                                                            class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                                                        @error('description')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-12 mt-3">
                                                        <button type="submit" class="btn btn-warning w-100"><i
                                                                class="fa fa-plus me-1"></i> Add Task</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach ($board->tasks->where('status', 'done') as $task)
                                        <div class="kanban-task card mb-2 shadow-sm" data-task-id="{{ $task->id }}"
                                            style="background-color: #fff; border: 1px solid #ddd;">
                                            <div class="card-body p-3">
                                                <h5 class="card-title mb-2" style="color: rgb(0, 0, 0);">
                                                    {{ $task->title }}</h5>
                                                <p class="card-text text-muted">
                                                    {{ $task->description ?: 'No description' }}</p>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('marketing.kanban.task.edit', $task) }}"
                                                        class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('marketing.kanban.task.destroy', $task) }}"
                                                        method="POST" style="display:inline;" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                            title="Delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .kanban-column {
            transition: all 0.3s ease;
        }

        .kanban-task {
            cursor: move;
            transition: transform 0.3s ease;
        }

        .kanban-task:hover {
            transform: translateY(-2px);
        }

        .kanban-task.dragging {
            opacity: 0.7;
            transform: scale(1.02);
        }

        .form-label {
            margin-bottom: 0.25rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.375rem;
        }

        .btn {
            border-radius: 0.375rem;
        }

        .invalid-feedback {
            color: #dc3545;
        }

        .highlight-row {
            background-color: #fff3cd !important;
            border-left: 4px solid #ffc107;
        }

        /* Ensure columns take full available width */
        .kanban-columns .col-md-4 {
            display: flex;
            flex: 1 1 33.3333%;
            /* Ensure equal width for each column */
            max-width: 33.3333%;
            /* Prevent columns from exceeding 1/3 of the row */
            padding-right: 15px;
            /* Maintain gutter spacing */
            padding-left: 15px;
        }

        .kanban-columns {
            margin-left: -15px;
            /* Offset container padding */
            margin-right: -15px;
        }

        .kanban-column {
            width: 100%;
            /* Ensure column content takes full width of its container */
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.3/dragula.min.js"></script>
    <script>
        document.querySelectorAll('.kanban-columns').forEach(board => {
            dragula([
                board.querySelector('.todo'),
                board.querySelector('.doing'),
                board.querySelector('.done')
            ]).on('drop', function(el, target, source, sibling) {
                const taskId = el.dataset.taskId;
                const newStatus = target.dataset.status;
                fetch('{{ route('marketing.kanban.update-task') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        task_id: taskId,
                        status: newStatus
                    })
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Task status updated!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Failed to update task status.'
                        });
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'An error occurred while updating the task.'
                    });
                });
            });
        });

        // SweetAlert for delete confirmation
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Yakin ingin menghapus task ini?',
                    text: "Task yang dihapus tidak bisa dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
