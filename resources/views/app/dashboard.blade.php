@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>

        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-0">{{ $message }}</h4>
            </div>
        </div>
    </div>
@endsection
