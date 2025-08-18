@extends('layouts.app')

@section('content')
<div class="container-fluid">

 {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('marketing.mindmap') }}">Mind Map</a></li>
            <li class="breadcrumb-item active" aria-current="page">List</li>
        </ol>
    </nav>
    {{-- Form buat Mind Map baru --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">Create New Mind Map</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('marketing.mindmap.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Mind Map Name</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="1">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-warning">Create</button>
                </div>
            </form>
        </div>
    </div>

    {{-- List Mind Maps --}}
    @forelse($mindmaps as $mindmap)
        <div class="card shadow-sm mb-5">
            <div class="card-header bg-light">
                <h4 class="mb-0">{{ $mindmap->name }}</h4>
                <small class="text-muted">{{ $mindmap->description }}</small>
            </div>
            <div class="card-body">

                {{-- Form Node Baru --}}
                <div class="mb-4">
                    <h6>Add New Node</h6>
                    <form action="{{ route('marketing.mindmap.node.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="mind_map_id" value="{{ $mindmap->id }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="title" placeholder="Node Title"
                                    class="form-control @error('title') is-invalid @enderror" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="content" placeholder="Node Content"
                                    class="form-control @error('content') is-invalid @enderror">
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <select name="parent_id"
                                    class="form-select @error('parent_id') is-invalid @enderror">
                                    <option value="">Root Level</option>
                                    @foreach($mindmap->nodes as $node)
                                        <option value="{{ $node->id }}">{{ $node->title }}</option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-warning w-100">Add</button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Mindmap Viewer (TIDAK DIUBAH) --}}
                <div class="mb-4">
                    @if($mindmap->nodes->isNotEmpty())
                        <div id="mindmap-{{ $mindmap->id }}" class="jsmind-container"
                            style="height: 500px; border: 1px solid #ccc; background: #f9f9f9;"></div>
                    @else
                        <p class="text-warning">No nodes available. Add some to visualize.</p>
                    @endif
                </div>

                {{-- Daftar Node --}}
                <h6>Node List</h6>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Content</th>
                                <th style="width:150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mindmap->nodes as $node)
                                <tr>
                                    <td><strong>{{ $node->title }}</strong></td>
                                    <td>{{ $node->content ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('marketing.mindmap.node.edit', $node) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('marketing.mindmap.node.destroy', $node) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if($mindmap->nodes->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No nodes available</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    @empty
        <div class="alert alert-info">No Mind Maps available.</div>
    @endforelse
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
                        theme: 'warning'
                    });

                    jm.show(mind_data);
                    window.jmInstances['mindmap-{{ $mindmap->id }}'] = jm;

                    // Tooltip content
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
