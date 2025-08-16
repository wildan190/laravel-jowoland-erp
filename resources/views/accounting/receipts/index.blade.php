@extends('layouts.app')

@section('title', 'Daftar Kwitansi')

@section('content')
    <div class="container-fluid">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kwitansi</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Daftar Kwitansi</h4>
            <a href="{{ route('accounting.receipts.create') }}" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> Buat Kwitansi
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Nomor Kwitansi</th>
                            <th>Invoice</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($receipts as $receipt)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $receipt->receipt_number }}</td>
                                <td>{{ $receipt->invoice->invoice_number ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($receipt->date)->format('d/m/Y') }}</td>

                                <td>{{ number_format($receipt->amount, 2) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('accounting.receipts.show', $receipt) }}"
                                        class="btn btn-sm btn-info me-1" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('accounting.receipts.pdf', $receipt) }}" class="btn btn-sm btn-danger"
                                        title="PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <em>Belum ada data kwitansi.</em>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
