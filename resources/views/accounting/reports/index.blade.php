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

        {{-- Filter --}}
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
        <div class="row g-3">
            <div class="col-md-2">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h6>Total Pemasukan</h6>
                        <h4>Rp{{ number_format($totalIncome, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h6>Total Pembelian</h6>
                        <h4>Rp{{ number_format($totalPurchasing, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-dark">
                    <div class="card-body">
                        <h6>Total Budget Iklan</h6>
                        <h4>Rp{{ number_format($totalAdsBudget, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h6>Total Cicilan Kredit</h6>
                        <h4>Rp{{ number_format($totalLoanPayment, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h6>Total Gaji</h6>
                        <h4>Rp{{ number_format($totalSalary, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <h6>Total Pengeluaran</h6>
                        <h4>Rp{{ number_format($totalExpense, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h6>Saldo Akhir</h6>
                        <h4>Rp{{ number_format($balance, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Pemasukan --}}
        <div class="card shadow-sm mt-4">
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
                            <th class="text-end">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($incomes as $income)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($income->date)->format('d M Y') }}</td>
                                <td>{{ $income->invoice->contact->name ?? '-' }}</td>
                                <td>{{ $income->description }}</td>
                                <td class="text-end">Rp{{ number_format($income->amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total</th>
                            <th class="text-end">Rp{{ number_format($totalIncome, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Detail Pembelian --}}
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0">Detail Pembelian</h6>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchasings as $purchase)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d M Y') }}</td>
                                <td>{{ $purchase->item_name }}</td>
                                <td class="text-end">{{ $purchase->quantity }}</td>
                                <td class="text-end">Rp{{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total</th>
                            <th class="text-end">Rp{{ number_format($totalPurchasing, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Detail Gaji --}}
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">Detail Gaji</h6>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Karyawan</th>
                            <th class="text-end">Gaji Pokok</th>
                            <th class="text-end">Tunjangan</th>
                            <th class="text-end">Potongan</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payrolls as $payroll)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($payroll->pay_date)->format('d M Y') }}</td>
                                <td>{{ $payroll->employee->name ?? '-' }}</td>
                                <td class="text-end">Rp{{ number_format($payroll->employee->salary ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="text-end">Rp{{ number_format($payroll->allowance ?? 0, 0, ',', '.') }}</td>
                                <td class="text-end">Rp{{ number_format($payroll->deduction ?? 0, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    Rp{{ number_format(($payroll->employee->salary ?? 0) + ($payroll->allowance ?? 0) - ($payroll->deduction ?? 0), 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">Total</th>
                            <th class="text-end">Rp{{ number_format($totalSalary, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Detail Kredit / Hutang --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning">
                <h6 class="mb-0">Detail Kredit / Hutang</h6>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Vendor / Bank</th>
                            <th>Pokok Hutang</th>
                            <th>Bunga (%)</th>
                            <th>Lama Cicilan (bulan)</th>
                            <th>Bulan</th>
                            <th>Cicilan Per Bulan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loanPayments as $payment)
                            <tr>
                                <td>{{ $payment['vendor'] }}</td>
                                <td>Rp{{ number_format($payment['principal'], 0, ',', '.') }}</td>
                                <td>{{ $payment['interest_rate'] }}</td>
                                <td>{{ $payment['installments'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment['due_date'])->format('M Y') }}</td>
                                <td>Rp{{ number_format($payment['monthly_payment'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Detail Ads Plan --}}
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-dark text-white">
                <h6 class="mb-0">Detail Rencana Iklan (Ads Plan)</h6>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Nama Campaign</th>
                            <th>Platform</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th class="text-end">Budget</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($adsPlans as $ads)
                            <tr>
                                <td>{{ $ads->name }}</td>
                                <td>{{ $ads->platform }}</td>
                                <td>{{ \Carbon\Carbon::parse($ads->start_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($ads->end_date)->format('d M Y') }}</td>
                                <td class="text-end">Rp{{ number_format($ads->budget, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada Ads Plan</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Total</th>
                            <th class="text-end">Rp{{ number_format($totalAdsBudget, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
@endsection
