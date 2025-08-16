@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1>Kanban Board</h1>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <!-- Form for creating a new Kanban board -->
            <form action="{{ route('marketing.kanban') }}" method="POST" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Board Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Create Board</button>
            </form>

            <!-- Form for creating a new task -->
            @foreach($boards as $board)
                <h2>{{ $board->name }}</h2>
                <p>{{ $board->description }}</p>
                <form action="{{ route('marketing.kanban.task.store') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="kanban_board_id" value="{{ $board->id }}">
                    <div class="mb-3">
                        <label for="task-title-{{ $board->id }}" class="form-label">Task Title</label>
                        <input type="text" name="title" id="task-title-{{ $board->id }}" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="task-description-{{ $board->id }}" class="form-label">Task Description</label>
                        <textarea name="description" id="task-description-{{ $board->id }}" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="task-status-{{ $board->id }}" class="form-label">Status</label>
                        <select name="status" id="task-status-{{ $board->id }}" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="todo" {{ old('status') == 'todo' ? 'selected' : '' }}>Todo</option>
                            <option value="doing" {{ old('status') == 'doing' ? 'selected' : '' }}>Doing</option>
                            <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </form>

                <div class="kanban-columns" data-board-id="{{ $board->id }}">
                    <div class="kanban-column todo" data-status="todo">
                        <h3>Todo</h3>
                        @foreach($board->tasks->where('status', 'todo') as $task)
                            <div class="kanban-task" data-task-id="{{ $task->id }}">
                                <div>{{ $task->title }}</div>
                                <div>{{ $task->description }}</div>
                                <div>
                                    <a href="{{ route('marketing.kanban.task.edit', $task) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('marketing.kanban.task.destroy', $task) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="kanban-column doing" data-status="doing">
                        <h3>Doing</h3>
                        @foreach($board->tasks->where('status', 'doing') as $task)
                            <div class="kanban-task" data-task-id="{{ $task->id }}">
                                <div>{{ $task->title }}</div>
                                <div>{{ $task->description }}</div>
                                <div>
                                    <a href="{{ route('marketing.kanban.task.edit', $task) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('marketing.kanban.task.destroy', $task) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="kanban-column done" data-status="done">
                        <h3>Done</h3>
                        @foreach($board->tasks->where('status', 'done') as $task)
                            <div class="kanban-task" data-task-id="{{ $task->id }}">
                                <div>{{ $task->title }}</div>
                                <div>{{ $task->description }}</div>
                                <div>
                                    <a href="{{ route('marketing.kanban.task.edit', $task) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('marketing.kanban.task.destroy', $task) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
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
                    body: JSON.stringify({ task_id: taskId, status: newStatus })
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        alert('Task status updated!');
                    }
                });
            });
        });
    </script>
@endsection