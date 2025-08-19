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
                    <div class="border rounded p-2" style="max-height: 250px; overflow-y: auto;">
                        @foreach ($employees as $employee)
                            @php
                                $checked = false;
                                if(old('employee_ids')) {
                                    $checked = in_array($employee->id, old('employee_ids'));
                                }
                            @endphp
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input employee-check"
                                       id="employee_{{ $employee->id }}"
                                       value="{{ $employee->id }}"
                                       data-name="{{ $employee->name }}"
                                       data-salary="{{ $employee->salary }}"
                                       {{ $checked ? 'checked' : '' }}>
                                <label class="form-check-label" for="employee_{{ $employee->id }}">
                                    {{ $employee->name }} (Rp {{ number_format($employee->salary, 0, ',', '.') }})
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('employee_ids')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Container untuk form per karyawan --}}
                <div id="employee-forms">
                    @if(old('employee_ids'))
                        @foreach(old('employee_ids') as $index => $empId)
                            @php
                                $employee = $employees->firstWhere('id', $empId);
                                $basic_salary = old('basic_salaries.' . $index, $employee->salary);
                                $allowance = old('allowances.' . $index, 0);
                                $note = old('notes.' . $index, '');
                            @endphp
                            <div id="form-{{ $empId }}" class="card p-3 mb-3 border">
                                <h5>{{ $employee->name }}</h5>
                                <input type="hidden" name="employee_ids[]" value="{{ $empId }}">

                                <div class="mb-2">
                                    <label class="form-label">Gaji Pokok</label>
                                    <input type="number" name="basic_salaries[]" class="form-control" value="{{ $basic_salary }}" readonly>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Tunjangan</label>
                                    <input type="number" name="allowances[]" class="form-control" value="{{ $allowance }}">
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Catatan</label>
                                    <textarea name="notes[]" class="form-control" rows="2">{{ $note }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Tanggal Gaji --}}
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="pay_date"
                           class="form-control @error('pay_date') is-invalid @enderror"
                           value="{{ old('pay_date', date('Y-m-d')) }}" required>
                    @error('pay_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="d-flex justify-content-between mt-3">
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

{{-- Script untuk form dinamis --}}
<script>
    const container = document.getElementById('employee-forms');

    function createForm(employeeId, name, salary) {
        if(document.getElementById('form-' + employeeId)) return; // prevent duplicate

        const div = document.createElement('div');
        div.id = `form-${employeeId}`;
        div.classList.add('card', 'p-3', 'mb-3', 'border');

        div.innerHTML = `
            <h5>${name}</h5>
            <input type="hidden" name="employee_ids[]" value="${employeeId}">

            <div class="mb-2">
                <label class="form-label">Gaji Pokok</label>
                <input type="number" name="basic_salaries[]" class="form-control" value="${salary}" readonly>
            </div>

            <div class="mb-2">
                <label class="form-label">Tunjangan</label>
                <input type="number" name="allowances[]" class="form-control" value="0">
            </div>

            <div class="mb-2">
                <label class="form-label">Catatan</label>
                <textarea name="notes[]" class="form-control" rows="2"></textarea>
            </div>
        `;

        container.appendChild(div);
    }

    function removeForm(employeeId) {
        const form = document.getElementById(`form-${employeeId}`);
        if (form) form.remove();
    }

    document.querySelectorAll('.employee-check').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const id = this.value;
            const name = this.dataset.name;
            const salary = this.dataset.salary;

            if(this.checked) {
                createForm(id, name, salary);
            } else {
                removeForm(id);
            }
        });
    });
</script>
@endsection
