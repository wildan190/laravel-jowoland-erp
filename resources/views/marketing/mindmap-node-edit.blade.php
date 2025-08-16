@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1>Edit Node for {{ $mindmap->name }}</h1>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form action="{{ route('marketing.mindmap.node.update', $node) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="title" class="form-label">Node Title</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $node->title) }}" required>
                    @error('title')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Node Content</label>
                    <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror">{{ old('content', $node->content) }}</textarea>
                    @error('content')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="parent_id" class="form-label">Parent Node</label>
                    <select name="parent_id" id="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                        <option value="">No Parent (Root Level)</option>
                        @foreach($nodes as $parentNode)
                            <option value="{{ $parentNode->id }}" {{ old('parent_id', $node->parent_id) == $parentNode->id ? 'selected' : '' }}>{{ $parentNode->title }}</option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Update Node</button>
                <a href="{{ route('marketing.mindmap') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection