@extends('layouts.app')

@section('title', 'Tambah Kontak')

@section('content')
<div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacts.index') }}">Kontak</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Kontak</li>
            </ol>
        </nav>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('contacts.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Telepon</label>
                        <input type="text" name="phone" id="phone" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="company" class="form-label">Perusahaan</label>
                        <input type="text" name="company" id="company" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <input type="text" name="address" id="address" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('contacts.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Kembali
                    </a>
                </form>
            </div>
        </div>
    </div>
@endsection
