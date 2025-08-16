@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('accounting.invoices.index') }}">Invoices</a></li>
            <li class="breadcrumb-item active" aria-current="page">Invoice {{ $invoice->invoice_number }}</li>
        </ol>
    </nav>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Perusahaan" style="height:70px;">
                </div>
                <div class="text-end" style="line-height:1.2;">
                    <h4 class="fw-bold mb-1">PT. Jowoland Construction</h4>
                    <div>Ketitang, Godong, Grobogan, Jawa Tengah</div>
                    <div>0852-8074-9218 | info@jowolandborepile.com</div>
                    <div>NPWP: 01.234.567.8-999.000</div>
                </div>
            </div>

            {{-- Invoice Info --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-primary mb-2">Invoice From</h5>
                    <div><strong>PT. Jowoland Construction</strong></div>
                    <div>Ketitang, Godong, Grobogan, Jawa Tengah</div>
                    <div>0852-8074-9218</div>
                    <div>info@jowolandborepile.com</div>
                    <div>NPWP: 01.234.567.8-999.000</div>
                </div>
                <div class="col-md-6 text-end">
                    <h5 class="text-primary mb-2">Invoice To</h5>
                    <div><strong>{{ $invoice->project->contact->company }}</strong></div>
                    <div>{{ $invoice->project->contact->name }}</div>
                    <div>{{ $invoice->project->contact->email }}</div>
                    <div>{{ $invoice->project->contact->phone }}</div>
                    <div>{{ $invoice->project->contact->address }}</div>
                </div>
            </div>

            {{-- Invoice Details --}}
            <div class="mb-4">
                <h5 class="text-primary mb-3">Invoice Details</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>Invoice Number</th>
                        <td>{{ $invoice->invoice_number }}</td>
                        <th>Invoice Date</th>
                        <td>{{ date('d/m/Y', strtotime($invoice->created_at)) }}</td>
                    </tr>
                    <tr>
                        <th>Project</th>
                        <td>{{ $invoice->project->name }}</td>
                        <th>Due Date</th>
                        <td>{{ $invoice->due_date }}</td>
                    </tr>
                </table>
            </div>

            {{-- Rincian Tagihan --}}
            <h5 class="text-primary mb-3">Rincian Tagihan</h5>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Service</th>
                        <th class="text-end">Harga (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $invoice->project->name }}</td>
                        <td class="text-end">{{ number_format($invoice->project_amount, 0, ',', '.') }}</td>
                    </tr>
                    @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Subtotal</th>
                        <th class="text-end">{{ number_format($invoice->subtotal, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th>PPN (11%)</th>
                        <th class="text-end">{{ number_format($invoice->tax, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th>Grand Total</th>
                        <th class="text-end">{{ number_format($invoice->grand_total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>

            {{-- Actions --}}
            <div class="mt-4 text-end">
                <a href="{{ route('accounting.invoices.pdf', $invoice->id) }}" class="btn btn-danger me-2">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>
                <a href="{{ route('accounting.invoices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
