@extends('layouts.app')

@section('title', 'Edit Kontak')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('contacts.index') }}">Kontak</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Edit Kontak</h5>

            <form action="{{ route('contacts.update', $contact) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           name="name" value="{{ old('name', $contact->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email', $contact->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Telepon</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                           name="phone" value="{{ old('phone', $contact->phone) }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="company" class="form-label">Perusahaan</label>
                    <input type="text" class="form-control @error('company') is-invalid @enderror"
                           name="perusahaan" value="{{ old('company', $contact->company) }}">
                    @error('company') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                           name="address" value="{{ old('address', $contact->address) }}">
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror"
                              name="notes" rows="3">{{ old('notes', $contact->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
