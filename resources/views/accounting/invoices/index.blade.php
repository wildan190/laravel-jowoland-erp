@extends('layouts.app')

@section('title', 'Manajemen Invoice')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Invoices</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Daftar Invoice</h4>
        <a href="{{ route('accounting.invoices.create') }}" class="btn btn-warning">
            <i class="fa fa-plus me-1"></i> Tambah Invoice
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Invoice #</th>
                        <th>Project</th>
                        <th>Contact</th>
                        <th>Grand Total</th>
                        <th>Due Date</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->project->name }}</td>
                            <td>{{ $invoice->project->contact->name ?? '-' }}</td>
                            <td>{{ number_format($invoice->grand_total, 2) }}</td>
                            <td>{{ $invoice->due_date }}</td>
                            <td class="text-center">
                                <a href="{{ route('accounting.invoices.show', $invoice->id) }}" class="btn btn-sm btn-info me-1" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('accounting.invoices.pdf', $invoice->id) }}" class="btn btn-sm btn-danger" title="Download PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <em>Belum ada data invoice.</em>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
