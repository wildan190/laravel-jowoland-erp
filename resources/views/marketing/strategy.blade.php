@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1>Marketing Strategy</h1>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form action="{{ route('marketing.strategy') }}" method="POST" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Strategy Name</label>
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
                <div class="mb-3">
                    <label for="prompt" class="form-label">AI Prompt for Generation</label>
                    <textarea name="prompt" id="prompt" class="form-control @error('prompt') is-invalid @enderror" required>{{ old('prompt') }}</textarea>
                    @error('prompt')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Generate & Create Strategy</button>
            </form>

            @foreach($strategies as $strategy)
                <div class="card mb-3">
                    <div class="card-body">
                        <h2>{{ $strategy->name }}</h2>
                        <p>{{ $strategy->description }}</p>
                        <pre>{{ $strategy->generated_content }}</pre>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection