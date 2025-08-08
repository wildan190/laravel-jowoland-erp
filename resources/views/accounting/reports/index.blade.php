@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
    <div class="container-fluid">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Laporan Keuangan</li>
            </ol>
        </nav>

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">Filter Periode</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('accounting.reports.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h6>Total Pemasukan</h6>
                        <h4>Rp{{ number_format($totalIncome, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h6>Total Pengeluaran</h6>
                        <h4>Rp{{ number_format($totalExpense, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h6>Total Gaji</h6>
                        <h4>Rp{{ number_format($totalSalary, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h6>Saldo Akhir</h6>
                        <h4>Rp{{ number_format($balance, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Pemasukan --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">Detail Pemasukan</h6>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Client</th>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incomes as $income)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($income->date)->format('d M Y') }}</td>
                                <td>{{ $income->deal->contact->company ?? '-' }}</td>
                                <td>{{ $income->description }}</td>
                                <td>Rp{{ number_format($income->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Detail Pembelian --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0">Detail Pembelian</h6>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchasings as $purchase)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d M Y') }}</td>
                                <td>{{ $purchase->item_name }}</td>
                                <td>{{ $purchase->quantity }}</td>
                                <td>Rp{{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Detail Gaji --}}
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">Detail Gaji</h6>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Karyawan</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payrolls as $payroll)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($payroll->pay_date)->format('d M Y') }}</td>
                                <td>{{ $payroll->employee->name ?? '-' }}</td>
                                <td>Rp{{ number_format($payroll->employee->salary ?? 0, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($payroll->allowance ?? 0, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format(($payroll->employee->salary ?? 0) + ($payroll->allowance ?? 0), 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
