@extends('layouts.app')

@section('title', 'Edit Pemasukan')

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('accounting.incomes.index') }}">Pemasukan</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Edit Pemasukan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('accounting.incomes.update', $income) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('accounting.incomes.form')
                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('accounting.incomes.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
