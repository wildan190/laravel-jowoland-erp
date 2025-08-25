@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">User Management</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Panel Kiri: Tambah User --}}
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">Tambah User</div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <input type="text" name="name" class="form-control mb-2" placeholder="Nama User" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror

                        <input type="email" name="email" class="form-control mb-2" placeholder="Email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror

                        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
                        @error('password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror

                        <button class="btn btn-primary btn-sm">Tambah User</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Panel Kanan: Daftar User --}}
        <div class="col-md-8">
            <div class="card" style="max-height:500px; overflow-y:auto;">
                <div class="card-header">Daftar User</div>
                <ul class="list-group list-group-flush">
                    @foreach($users as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $user->name }} ({{ $user->email }})</span>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
