@extends('layouts.app')

@section('title', 'Buat Kwitansi')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat Kwitansi</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('accounting.receipts.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="invoice_id" class="form-label">Invoice</label>
                    <select name="invoice_id" id="invoice_id" class="form-select @error('invoice_id') is-invalid @enderror">
                        <option value="">-- Pilih Invoice --</option>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}" data-total="{{ $invoice->grand_total }}">
                                {{ $invoice->invoice_number }} - {{ $invoice->project->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('invoice_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Jumlah (Rp)</label>
                    <input type="text" id="amount" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Tanggal</label>
                    <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}">
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="note" class="form-label">Catatan (opsional)</label>
                    <textarea name="note" id="note" class="form-control">{{ old('note') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Kwitansi</button>
            </form>
        </div>
    </div>
</div>

<script>
    const invoiceSelect = document.getElementById('invoice_id');
    const amountInput = document.getElementById('amount');

    invoiceSelect.addEventListener('change', function() {
        const selected = this.selectedOptions[0];
        const total = selected ? selected.getAttribute('data-total') : '';
        amountInput.value = total ? parseFloat(total).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }) : '';
    });
</script>
@endsection
