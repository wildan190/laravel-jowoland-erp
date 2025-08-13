@extends('layouts.app')

@section('title', 'Tambah Deal')

@section('content')
<div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('deal.index') }}">Deal</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('deal.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Judul Deal</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Kontak</label>
                        <select name="contact_id" class="form-control" required>
                            @foreach ($contacts as $contact)
                                <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Tahapan</label>
                        <select name="pipeline_stage_id" class="form-control" required>
                            @foreach ($stages as $stage)
                                <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nilai</label>
                        <input type="number" name="value" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="open">Open</option>
                            <option value="won">Won</option>
                            <option value="lost">Lost</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Catatan</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
