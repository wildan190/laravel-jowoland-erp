@extends('layouts.app')

@section('title', 'Edit Pembelian')

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('accounting.purchasings.index') }}">Pembelian</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Edit Pembelian</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('accounting.purchasings.update', $purchasing) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('accounting.purchasings.form')
                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('accounting.purchasings.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
