@extends('layouts.app')

@section('title', 'Edit Proyek')

@section('content')
    <div class="container">
    
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Project Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Proyek</li>
            </ol>
        </nav>

        <h4 class="mb-3">Edit Proyek</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('projects.update', $project) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Proyek</label>
                        <input type="text" name="name" id="name" class="form-control" required
                            value="{{ old('name', $project->name) }}">
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Lokasi</label>
                        <input type="text" name="location" id="location" class="form-control" required
                            value="{{ old('location', $project->location) }}">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $project->description) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
