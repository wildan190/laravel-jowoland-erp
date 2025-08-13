@extends('layouts.app')

@section('title', 'Edit Deal')

@section('content')
<div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('deal.index') }}">Deals</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('deal.update', $deal) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ $deal->title }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="value" class="form-label">Nilai</label>
                        <input type="number" name="value" id="value" class="form-control" value="{{ $deal->value }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="contact_id" class="form-label">Kontak</label>
                        <select name="contact_id" id="contact_id" class="form-select">
                            @foreach ($contacts as $contact)
                                <option value="{{ $contact->id }}" {{ $contact->id == $deal->contact_id ? 'selected' : '' }}>
                                    {{ $contact->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="pipeline_stage_id" class="form-label">Stage</label>
                        <select name="pipeline_stage_id" id="pipeline_stage_id" class="form-select">
                            @foreach ($stages as $stage)
                                <option value="{{ $stage->id }}" {{ $stage->id == $deal->pipeline_stage_id ? 'selected' : '' }}>
                                    {{ $stage->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="open" {{ $deal->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="won" {{ $deal->status == 'won' ? 'selected' : '' }}>Won</option>
                            <option value="lost" {{ $deal->status == 'lost' ? 'selected' : '' }}>Lost</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ $deal->notes }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('deal.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
