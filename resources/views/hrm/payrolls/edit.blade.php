@extends('layouts.app')

@section('title', 'Edit Penggajian')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}">Penggajian</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">
            <i class="fa fa-edit me-1"></i> Edit Data Penggajian
        </div>

        <div class="card-body">
            <form action="{{ route('payrolls.update', $payroll) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama Karyawan --}}
                <div class="mb-3">
                    <label class="form-label">Nama Karyawan</label>
                    <input type="text" class="form-control" value="{{ $payroll->employee->name }}" readonly>
                    <input type="hidden" name="employee_id" value="{{ $payroll->employee_id }}">
                </div>

                {{-- Gaji Pokok --}}
                <div class="mb-3">
                    <label class="form-label">Gaji Pokok</label>
                    <input type="number" class="form-control" value="{{ $payroll->employee->salary }}" readonly>
                </div>

                {{-- Tunjangan --}}
                <div class="mb-3">
                    <label class="form-label">Tunjangan</label>
                    <input type="number" name="allowance" class="form-control @error('allowance') is-invalid @enderror" value="{{ old('allowance', $payroll->allowance) }}" required>
                    @error('allowance')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tanggal --}}
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="pay_date" class="form-control @error('pay_date') is-invalid @enderror" value="{{ old('pay_date', $payroll->pay_date) }}" required>
                    @error('pay_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $payroll->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
