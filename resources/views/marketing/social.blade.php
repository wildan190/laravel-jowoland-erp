@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" class="text-decoration-none text-dark">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('marketing.kanban') }}" class="text-decoration-none text-dark">Kanban Boards</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page" style="color: rgb(255, 96, 0); font-weight: 600;">
                Social Media Marketing
            </li>
        </ol>
    </nav>

    <!-- Main Card -->
    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-header bg-warning text-white">
            <h1 class="h4 mb-0">📱 Social Media Marketing</h1>
        </div>
        <div class="card-body">
            <!-- Alert -->
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('marketing.social') }}" method="POST" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="platform" class="form-label fw-semibold">Platform</label>
                        <select name="platform" id="platform" class="form-select @error('platform') is-invalid @enderror" required>
                            <option value="">Select Platform...</option>
                            <option value="twitter" {{ old('platform') == 'twitter' ? 'selected' : '' }}>
                                🐦 Twitter
                            </option>
                            <option value="instagram" {{ old('platform') == 'instagram' ? 'selected' : '' }}>
                                📷 Instagram
                            </option>
                            <option value="facebook" {{ old('platform') == 'facebook' ? 'selected' : '' }}>
                                👥 Facebook
                            </option>
                            <option value="linkedin" {{ old('platform') == 'linkedin' ? 'selected' : '' }}>
                                💼 LinkedIn
                            </option>
                            <option value="tiktok" {{ old('platform') == 'tiktok' ? 'selected' : '' }}>
                                🎵 TikTok
                            </option>
                        </select>
                        @error('platform')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="scheduled_at" class="form-label fw-semibold">Schedule Time</label>
                        <input 
                            type="datetime-local" 
                            name="scheduled_at" 
                            id="scheduled_at" 
                            class="form-control @error('scheduled_at') is-invalid @enderror" 
                            value="{{ old('scheduled_at') }}"
                            min="{{ now()->format('Y-m-d\TH:i') }}">
                        @error('scheduled_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Leave empty for immediate posting</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="prompt" class="form-label fw-semibold">AI Prompt for Post Generation</label>
                    <textarea 
                        name="prompt" 
                        id="prompt" 
                        rows="4"
                        class="form-control @error('prompt') is-invalid @enderror" 
                        placeholder="Describe the content you want to generate..."
                        required>{{ old('prompt') }}</textarea>
                    @error('prompt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Be specific about tone, target audience, and key messages</div>
                </div>

                <button type="submit" class="btn btn-warning w-100 py-2">
                    🚀 Generate & Create Post
                </button>
            </form>

            <hr class="my-4">

            <!-- Generated Posts -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="h5 mb-0">📋 Generated Posts</h3>
                <span class="badge bg-secondary">{{ count($posts) }} Posts</span>
            </div>

            @forelse($posts as $post)
                <div class="card mb-3 shadow-sm">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="platform-badge me-2">
                                    @switch($post->platform)
                                        @case('twitter')
                                            <span class="badge bg-info">🐦 Twitter</span>
                                            @break
                                        @case('instagram')
                                            <span class="badge bg-warning">📷 Instagram</span>
                                            @break
                                        @case('facebook')
                                            <span class="badge bg-warning">👥 Facebook</span>
                                            @break
                                        @case('linkedin')
                                            <span class="badge bg-dark">💼 LinkedIn</span>
                                            @break
                                        @case('tiktok')
                                            <span class="badge bg-danger">🎵 TikTok</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($post->platform) }}</span>
                                    @endswitch
                                </span>
                                <div>
                                    <h6 class="mb-0">{{ ucfirst($post->platform) }} Post</h6>
                                    <small class="text-muted">
                                        @if($post->scheduled_at)
                                            @php
                                                $scheduledDate = is_string($post->scheduled_at) ? 
                                                    \Carbon\Carbon::parse($post->scheduled_at) : 
                                                    $post->scheduled_at;
                                            @endphp
                                            📅 Scheduled: {{ $scheduledDate->format('M d, Y - H:i') }}
                                            @if($scheduledDate->isFuture())
                                                <span class="badge bg-warning text-dark ms-1">Pending</span>
                                            @else
                                                <span class="badge bg-success ms-1">Posted</span>
                                            @endif
                                        @else
                                            ⚡ Immediate posting
                                            <span class="badge bg-success ms-1">Ready</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <button 
                                class="btn btn-sm btn-link p-0 text-decoration-none fw-bold fs-5 post-toggle"
                                onclick="togglePost({{ $loop->index }})"
                                id="post-btn-{{ $loop->index }}"
                                style="color: #6c757d; border: none; background: none;">
                                +
                            </button>
                        </div>
                    </div>
                    <div class="post-content" id="post-content-{{ $loop->index }}" style="display: none;">
                        <div class="card-body">
                            <div class="bg-light p-3 rounded border-start border-4 border-warning">
                                <div style="white-space: pre-wrap; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6;">
                                    {{ $post->generated_content }}
                                </div>
                            </div>
                            
                            <!-- Post Actions -->
                            <div class="d-flex gap-2 mt-3">
                                <button class="btn btn-sm btn-outline-warning">
                                    📝 Edit Content
                                </button>
                                <button class="btn btn-sm btn-outline-success">
                                    📤 Reschedule
                                </button>
                                <button class="btn btn-sm btn-outline-info">
                                    📋 Copy Text
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    🗑️ Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="text-muted">
                        <svg width="64" height="64" fill="currentColor" class="bi bi-chat-square-text mb-3" viewBox="0 0 16 16">
                            <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                            <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                        </svg>
                        <p class="h6">No social media posts generated yet</p>
                        <p class="small mb-0">Create your first post using the form above</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function togglePost(index) {
    const content = document.getElementById('post-content-' + index);
    const button = document.getElementById('post-btn-' + index);
    
    if (content.style.display === 'none' || content.style.display === '') {
        // Show content
        content.style.display = 'block';
        button.textContent = '−';
        button.style.color = '#0d6efd';
        
        // Smooth animation
        content.style.opacity = '0';
        content.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            content.style.transition = 'all 0.3s ease';
            content.style.opacity = '1';
            content.style.transform = 'translateY(0)';
        }, 10);
        
    } else {
        // Hide content
        content.style.transition = 'all 0.3s ease';
        content.style.opacity = '0';
        content.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            content.style.display = 'none';
            button.textContent = '+';
            button.style.color = '#6c757d';
        }, 300);
    }
}

// Copy text functionality
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'alert alert-success position-fixed top-0 end-0 m-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = '✅ Text copied to clipboard!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    });
}

// Add hover effects and copy functionality
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.post-toggle');
    
    toggleButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.2)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Add copy functionality to copy buttons
    document.querySelectorAll('[data-action="copy"]').forEach(button => {
        button.addEventListener('click', function() {
            const content = this.getAttribute('data-content');
            copyToClipboard(content);
        });
    });
});

// Set minimum datetime to current time
document.addEventListener('DOMContentLoaded', function() {
    const scheduledInput = document.getElementById('scheduled_at');
    if (scheduledInput) {
        const now = new Date();
        const localDateTime = now.getFullYear() + '-' + 
            String(now.getMonth() + 1).padStart(2, '0') + '-' + 
            String(now.getDate()).padStart(2, '0') + 'T' + 
            String(now.getHours()).padStart(2, '0') + ':' + 
            String(now.getMinutes()).padStart(2, '0');
        scheduledInput.min = localDateTime;
    }
});
</script>

<style>
.post-toggle {
    cursor: pointer;
    user-select: none;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.post-toggle:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.post-content {
    border-top: 1px solid #dee2e6;
}

.card {
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
}

.platform-badge .badge {
    font-size: 0.85rem;
}

.border-start {
    border-left: 4px solid #0d6efd !important;
}

.form-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
}

.btn-group-sm > .btn, .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-wrap: wrap;
    }
    
    .btn-sm {
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection