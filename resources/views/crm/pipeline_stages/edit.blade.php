@extends('layouts.app')

@section('title', 'Edit Pipeline Stage')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('pipeline.index') }}">Pipeline</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Stage</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('pipeline.update', $stages) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Stage</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name', $stages->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="order" class="form-label">Urutan</label>
                        <input type="number" name="order" id="order" class="form-control"
                            value="{{ old('order', $stages->order) }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('pipeline.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

@endsection
