@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1>Social Media Marketing</h1>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form action="{{ route('marketing.social') }}" method="POST" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label for="platform" class="form-label">Platform</label>
                    <select name="platform" id="platform" class="form-control @error('platform') is-invalid @enderror" required>
                        <option value="twitter" {{ old('platform') == 'twitter' ? 'selected' : '' }}>Twitter</option>
                        <option value="instagram" {{ old('platform') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                        <option value="facebook" {{ old('platform') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                    </select>
                    @error('platform')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="scheduled_at" class="form-label">Schedule Time</label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control @error('scheduled_at') is-invalid @enderror" value="{{ old('scheduled_at') }}">
                    @error('scheduled_at')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="prompt" class="form-label">AI Prompt for Post Generation</label>
                    <textarea name="prompt" id="prompt" class="form-control @error('prompt') is-invalid @enderror" required>{{ old('prompt') }}</textarea>
                    @error('prompt')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Generate & Create Post</button>
            </form>

            @foreach($posts as $post)
                <div class="card mb-3">
                    <div class="card-body">
                        <h2>{{ $post->platform }}</h2>
                        <p>Scheduled: {{ $post->scheduled_at ? $post->scheduled_at->format('Y-m-d H:i') : 'Not scheduled' }}</p>
                        <pre>{{ $post->generated_content }}</pre>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection