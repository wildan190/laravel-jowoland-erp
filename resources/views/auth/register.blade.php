@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
        <h4 class="mb-3 text-center">Register</h4>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name">Nama</label>
                <input id="name" type="text" name="name" class="form-control" required autofocus>
            </div>

            <div class="mb-3">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button class="btn btn-warning w-100">Register</button>
        </form>

        <div class="text-center mt-3">
            Sudah punya akun? <a href="{{ route('login') }}">Login</a>
        </div>
    </div>
</div>
@endsection
