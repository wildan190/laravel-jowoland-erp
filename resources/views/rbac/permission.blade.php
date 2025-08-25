@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Permission Management</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Panel Kiri: Tambah Permission --}}
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">Tambah Permission</div>
                <div class="card-body">
                    <form action="{{ route('permissions.store') }}" method="POST">
                        @csrf
                        <input type="text" name="name" class="form-control mb-2" placeholder="Nama Permission" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <button class="btn btn-primary btn-sm">Tambah Permission</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Panel Kanan: Daftar Permission --}}
        <div class="col-md-8">
            <div class="card" style="max-height:500px; overflow-y:auto;">
                <div class="card-header">Daftar Permission</div>
                <ul class="list-group list-group-flush">
                    @foreach($permissions as $permission)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $permission->name }}</span>
                            <form action="{{ route('permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Hapus permission?')">
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
