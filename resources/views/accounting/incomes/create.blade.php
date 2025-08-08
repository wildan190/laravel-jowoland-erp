@extends('layouts.app')

@section('title', 'Tambah Pemasukan')

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('accounting.incomes.index') }}">Pemasukan</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Tambah Pemasukan</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('accounting.incomes.store') }}" method="POST">
                    @csrf
                    @include('accounting.incomes.form')
                    <button class="btn btn-primary">Simpan</button>
                    <a href="{{ route('accounting.incomes.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
