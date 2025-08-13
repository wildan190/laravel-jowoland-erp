@extends('layouts.app')

@section('title', 'Edit Hutang')

@section('content')
    <div class="container-fluid">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('accounting.loans.index') }}">Kredit / Hutang</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Edit Hutang</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('accounting.loans.update', $loan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label>Vendor / Bank</label>
                        <input type="text" name="vendor" class="form-control" value="{{ old('vendor', $loan->vendor) }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Pokok Hutang</label>
                        <input type="number" name="principal" class="form-control"
                            value="{{ old('principal', $loan->principal) }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Bunga (%)</label>
                        <input type="number" step="0.01" name="interest_rate" class="form-control"
                            value="{{ old('interest_rate', $loan->interest_rate) }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Lama Cicilan (bulan)</label>
                        <input type="number" name="installments" id="installments" class="form-control"
                            value="{{ old('installments', $loan->installments) }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="{{ old('start_date', optional($loan->start_date)->format('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-control"
                            value="{{ old('end_date', optional($loan->end_date)->format('Y-m-d')) }}" readonly>
                    </div>
                    {{-- <div class="mb-3">
                        <label>Tanggal Jatuh Tempo (opsional)</label>
                        <input type="date" name="due_date" class="form-control"
                            value="{{ old('due_date', optional($loan->due_date)->format('Y-m-d')) }}">
                    </div> --}}
                    <div class="mb-3">
                        <label>Keterangan</label>
                        <textarea name="description" class="form-control">{{ old('description', $loan->description) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('accounting.loans.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('start_date').addEventListener('change', updateEndDate);
        document.getElementById('installments').addEventListener('input', updateEndDate);

        function updateEndDate() {
            let startDate = document.getElementById('start_date').value;
            let months = parseInt(document.getElementById('installments').value);

            if (startDate && months > 0) {
                let date = new Date(startDate);
                date.setMonth(date.getMonth() + months);
                date.setDate(date.getDate() - 1);
                document.getElementById('end_date').value = date.toISOString().split('T')[0];
            }
        }
    </script>
@endsection
