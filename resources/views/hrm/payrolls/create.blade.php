@extends('layouts.app')

@section('title', 'Tambah Penggajian')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}">Penggajian</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">
            <i class="fa fa-plus me-1"></i> Tambah Data Penggajian
        </div>

        <div class="card-body">
            {{-- Tampilkan error global --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('payrolls.store') }}" method="POST">
                @csrf

                {{-- Pilih Karyawan --}}
                <div class="mb-3">
                    <label class="form-label">Karyawan</label>
                    <select name="employee_id" id="employee-select"
                        class="form-select @error('employee_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" data-salary="{{ $employee->salary }}"
                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }} (Rp {{ number_format($employee->salary, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Gaji Pokok --}}
                <div class="mb-3">
                    <label class="form-label">Gaji Pokok</label>
                    <input type="number" class="form-control" id="basic_salary" name="basic_salary"
                        value="{{ old('basic_salary') }}" readonly>
                    @error('basic_salary')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tunjangan --}}
                <div class="mb-3">
                    <label class="form-label">Tunjangan</label>
                    <input type="number" name="allowance"
                        class="form-control @error('allowance') is-invalid @enderror"
                        value="{{ old('allowance', 0) }}">
                    @error('allowance')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tanggal Gaji --}}
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="pay_date"
                        class="form-control @error('pay_date') is-invalid @enderror"
                        value="{{ old('pay_date') }}" required>
                    @error('pay_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" rows="3"
                        class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script autofill gaji pokok --}}
<script>
    document.getElementById('employee-select').addEventListener('change', function () {
        const salary = this.options[this.selectedIndex].getAttribute('data-salary');
        document.getElementById('basic_salary').value = salary || 0;
    });

    // Trigger saat halaman dimuat jika old() sudah ada
    window.addEventListener('DOMContentLoaded', function () {
        const selected = document.querySelector('#employee-select option:checked');
        if (selected && selected.dataset.salary) {
            document.getElementById('basic_salary').value = selected.dataset.salary;
        }
    });
</script>
@endsection
