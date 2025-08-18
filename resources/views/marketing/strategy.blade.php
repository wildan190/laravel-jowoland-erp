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
                    Marketing Strategy
                </li>
            </ol>
        </nav>

        <!-- Main Card -->
        <div class="card shadow-lg rounded-3 border-0">
            <div class="card-header bg-warning text-white">
                <h1 class="h4 mb-0">📈 Marketing Strategy</h1>
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
                <form action="{{ route('marketing.strategy') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Strategy Name</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                            placeholder="Enter strategy name..." required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror" placeholder="Briefly describe your strategy...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="prompt" class="form-label fw-semibold">AI Prompt for Generation</label>
                        <textarea name="prompt" id="prompt" rows="4" class="form-control @error('prompt') is-invalid @enderror"
                            placeholder="Write your AI prompt here..." required>{{ old('prompt') }}</textarea>
                        @error('prompt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-warning w-100 py-2">
                        🚀 Generate & Create Strategy
                    </button>
                </form>

                <hr class="my-4">

                <!-- Generated Strategies -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h5 mb-0">📋 Generated Strategies</h3>
                </div>

                @forelse($strategies as $strategy)
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-semibold">{{ $strategy->name }}</h6>
                                <button class="btn btn-sm btn-link p-0 text-decoration-none fw-bold fs-5 strategy-toggle"
                                    onclick="toggleStrategy({{ $loop->index }})" id="btn-{{ $loop->index }}"
                                    style="color: #6c757d; border: none; background: none;">
                                    +
                                </button>
                            </div>
                        </div>
                        <div class="strategy-content" id="content-{{ $loop->index }}" style="display: none;">
                            <div class="card-body">
                                @if ($strategy->description)
                                    <p class="text-muted mb-3">{{ $strategy->description }}</p>
                                @endif
                                <div class="bg-light p-3 rounded">
                                    <div
                                        style="white-space: pre-wrap; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6;">
                                        {!! nl2br(e($strategy->generated_content)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <svg width="64" height="64" fill="currentColor" class="bi bi-lightbulb mb-3"
                                viewBox="0 0 16 16">
                                <path
                                    d="M2 6a6 6 0 1 1 10.174 4.31c-.203.196-.359.4-.453.619l-.762 1.769A.5.5 0 0 1 10.5 13a.5.5 0 0 1 0 1 .5.5 0 0 1 0 1l-.224.447a1 1 0 0 1-.894.553H6.618a1 1 0 0 1-.894-.553L5.5 15a.5.5 0 0 1 0-1 .5.5 0 0 1 0-1 .5.5 0 0 1-.459-.31l-.762-1.77A1.964 1.964 0 0 0 3.826 10.31 6.19 6.19 0 0 1 2 6zm6-5a5 5 0 0 0-3.479 8.592c.263.254.514.564.676.941L5.83 12h4.342l.632-1.467c.162-.377.413-.687.676-.941A5 5 0 0 0 8 1z" />
                            </svg>
                            <p class="h6">No strategies generated yet</p>
                            <p class="small mb-0">Create your first marketing strategy using the form above</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function toggleStrategy(index) {
            const content = document.getElementById('content-' + index);
            const button = document.getElementById('btn-' + index);

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

        // Add hover effects
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.strategy-toggle');

            toggleButtons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.2)';
                    this.style.transition = 'transform 0.2s ease';
                });

                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>

    <style>
        .strategy-toggle {
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

        .strategy-toggle:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .strategy-content {
            border-top: 1px solid #dee2e6;
        }

        .card {
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }
    </style>
@endsection
