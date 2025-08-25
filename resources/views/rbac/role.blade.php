@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Role Management</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Panel Kiri: Tambah Role + Daftar Role --}}
        <div class="col-md-4">
            {{-- Tambah Role --}}
            <div class="card mb-3">
                <div class="card-header">Tambah Role</div>
                <div class="card-body">
                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf
                        <input type="text" name="name" class="form-control mb-2" placeholder="Nama Role" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror

                        <div class="mb-2" style="max-height:200px; overflow-y:auto;">
                            <label>Permissions:</label><br>
                            @foreach($permissions as $perm)
                                <label class="d-block">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                        {{ old('permissions') && in_array($perm->name, old('permissions')) ? 'checked' : '' }}>
                                    {{ $perm->name }}
                                </label>
                            @endforeach
                            @error('permissions')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-primary btn-sm">Tambah Role</button>
                    </form>
                </div>
            </div>

            {{-- Daftar Role --}}
            <div class="card" style="max-height:500px; overflow-y:auto;">
                <div class="card-header">Daftar Role</div>
                <ul class="list-group list-group-flush">
                    @foreach($roles as $role)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $role->name }}</span>
                            <div>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#usersModal{{ $role->id }}">
                                    Assign Users
                                </button>
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Panel Kanan: Role Detail --}}
        <div class="col-md-8">
            @foreach($roles as $role)
            <div class="card mb-3">
                <div class="card-header">Role: {{ $role->name }}</div>
                <div class="card-body">

                    {{-- Update Permissions --}}
                    <form action="{{ route('roles.update.permissions', $role) }}" method="POST">
                        @csrf
                        <h6>Permissions</h6>
                        <div style="max-height:200px; overflow-y:auto;">
                            @foreach($permissions as $perm)
                                <label class="d-block">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                        {{ $role->permissions->contains('name', $perm->name) ? 'checked' : '' }}>
                                    {{ $perm->name }}
                                </label>
                            @endforeach
                        </div>
                        <button class="btn btn-warning btn-sm mt-2">Update Permissions</button>
                    </form>

                </div>
            </div>

            {{-- Modal Assign Users --}}
            <div class="modal fade" id="usersModal{{ $role->id }}" tabindex="-1" aria-labelledby="usersModalLabel{{ $role->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('roles.assign.users', $role) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="usersModalLabel{{ $role->id }}">Assign Users to Role: {{ $role->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="max-height:400px; overflow-y:auto;">
                                @foreach($users as $user)
                                    <label class="d-block">
                                        <input type="checkbox" name="users[]" value="{{ $user->id }}"
                                            {{ $role->assignedUsers->contains($user->id) ? 'checked' : '' }}>
                                        {{ $user->name }}
                                    </label>
                                @endforeach
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success btn-sm">Assign Users</button>
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @endforeach
        </div>
    </div>
</div>
@endsection
