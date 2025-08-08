@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('accounting.transactions.index') }}">Transaksi</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Tambah Transaksi</h5>
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

                <form action="{{ route('accounting.transactions.store') }}" method="POST">
                    @csrf
                    @include('accounting.transactions.form')
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ route('accounting.transactions.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
