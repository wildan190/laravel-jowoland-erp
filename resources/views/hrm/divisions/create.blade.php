@extends('layouts.app')

@section('title', 'Tambah Divisi')

@section('content')
<div class="container">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('divisions.index') }}">Divisi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">
            <i class="fa fa-plus me-1"></i> Tambah Divisi
        </div>

        <div class="card-body">
            <form action="{{ route('divisions.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Divisi</label>
                    <input type="text" name="name" class="form-control" required placeholder="Masukkan nama divisi">
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('divisions.index') }}" class="btn btn-secondary me-2">
                        <i class="fa fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
