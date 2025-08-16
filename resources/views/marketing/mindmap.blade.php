@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1>Mind Map</h1>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Form untuk membuat Mind Map -->
            <form action="{{ route('marketing.mindmap.store') }}" method="POST" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Mind Map Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required>
                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description"
                        class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Create Mind Map</button>
            </form>

            @forelse($mindmaps as $mindmap)
                <h2>{{ $mindmap->name }}</h2>
                <p>{{ $mindmap->description }}</p>

                <!-- Form untuk membuat Node baru -->
                <form action="{{ route('marketing.mindmap.node.store') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="mind_map_id" value="{{ $mindmap->id }}">
                    <div class="mb-3">
                        <label for="node-title-{{ $mindmap->id }}" class="form-label">Node Title</label>
                        <input type="text" name="title" id="node-title-{{ $mindmap->id }}"
                            class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="node-content-{{ $mindmap->id }}" class="form-label">Node Content</label>
                        <textarea name="content" id="node-content-{{ $mindmap->id }}"
                            class="form-control @error('content') is-invalid @enderror"></textarea>
                        @error('content')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="parent-id-{{ $mindmap->id }}" class="form-label">Parent Node</label>
                        <select name="parent_id" id="parent-id-{{ $mindmap->id }}"
                            class="form-control @error('parent_id') is-invalid @enderror">
                            <option value="">No Parent (Root Level)</option>
                            @foreach($mindmap->nodes as $node)
                                <option value="{{ $node->id }}">{{ $node->title }}</option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Add Node</button>
                </form>

                <!-- Mind Map Visualization -->
                @if($mindmap->nodes->isNotEmpty())
                    <div id="mindmap-{{ $mindmap->id }}" class="jsmind-container"
                        style="height: 500px; border: 1px solid #ccc; background: #f9f9f9;"></div>
                @else
                    <p class="text-warning">No nodes available for this Mind Map. Add nodes to visualize.</p>
                @endif

                <!-- Node List -->
                <h3>Nodes</h3>
                <ul>
                    @foreach($mindmap->nodes as $node)
                        <li>
                            <strong>{{ $node->title }}</strong>: {{ $node->content ?? 'No content' }}
                            <div>
                                <a href="{{ route('marketing.mindmap.node.edit', $node) }}"
                                    class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('marketing.mindmap.node.destroy', $node) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @empty
                <p>No Mind Maps available.</p>
            @endforelse
        </div>
    </div>
@endsection

@section('scripts')
    {{-- load jsMind --}}
    <link type="text/css" rel="stylesheet" href="{{ asset('jsmind/style/jsmind.css') }}" />
    <script type="text/javascript" src="{{ asset('jsmind/js/jsmind.js') }}"></script>
    <script type="text/javascript" src="{{ asset('jsmind/js/jsmind.draggable.js') }}"></script>

    <script>
        window.jmInstances = {};

        @foreach($mindmaps as $mindmap)
            @if($mindmap->nodes->isNotEmpty())
                (function() {
                    const nodes = @json($mindmap->nodes);

                    const dataArray = nodes.map((n, index) => {
                        return {
                            id: n.id.toString(),
                            isroot: index === 0 ? true : undefined,
                            parentid: n.parent_id ? n.parent_id.toString() : undefined,
                            topic: n.title
                        };
                    });

                    const mind_data = {
                        meta: { name: '{{ e($mindmap->name) }}', author: 'User', version: '1.0' },
                        format: 'node_array',
                        data: dataArray
                    };

                    const jm = new jsMind({
                        container: 'mindmap-{{ $mindmap->id }}',
                        editable: true,
                        theme: 'primary'
                    });

                    jm.show(mind_data);
                    window.jmInstances['mindmap-{{ $mindmap->id }}'] = jm;

                    // Tambahkan tooltip dengan content
                    document.querySelectorAll('#mindmap-{{ $mindmap->id }} jmnode').forEach(nodeEl => {
                        const nodeId = nodeEl.getAttribute('nodeid');
                        const dbNode = nodes.find(n => n.id.toString() === nodeId);
                        if (dbNode && dbNode.content) {
                            nodeEl.setAttribute('title', dbNode.content);
                        }
                    });
                })();
            @endif
        @endforeach
    </script>
@endsection
