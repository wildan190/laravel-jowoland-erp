@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('accounting.reports.index') }}" class="text-decoration-none">Laporan Keuangan</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Tahun {{ $year }}
            </li>
        </ol>
    </nav>

    <h1 class="mb-4">📊 Laporan Keuangan Tahun {{ $year }}</h1>

    {{-- Filter Tahun --}}
    <form method="GET" class="mb-4 d-flex align-items-center gap-2">
        <label class="fw-bold">Pilih Tahun:</label>
        <select name="year" class="form-select w-auto" onchange="this.form.submit()">
            @for($y = now()->year; $y >= now()->year - 5; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </form>

    @php
        $totalYearIncome = collect($summary)->sum('income');
        $totalYearExpense = collect($summary)->sum('expense');
        $totalYearPayroll = collect($summary)->sum('payroll');
        $totalYearBalance = $totalYearIncome - ($totalYearExpense + $totalYearPayroll);
    @endphp

    {{-- Ringkasan Tahunan --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-success shadow">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Pendapatan</h5>
                    <h3>Rp {{ number_format($totalYearIncome, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Pengeluaran</h5>
                    <h3>Rp {{ number_format($totalYearExpense, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Gaji</h5>
                    <h3>Rp {{ number_format($totalYearPayroll, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow">
                <div class="card-body text-center">
                    <h5 class="card-title">Saldo Akhir Tahun</h5>
                    <h3>Rp {{ number_format($totalYearBalance, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Per Kuartal --}}
    @foreach($quarters as $q => $transactions)
        <div class="card mb-4 shadow">
            <div class="card-header bg-light">
                <h4 class="mb-0">
                    {{ $q }} 
                    <small class="text-muted">
                        (Pemasukan: Rp {{ number_format($summary[$q]['income'], 0, ',', '.') }},
                        Pengeluaran: Rp {{ number_format($summary[$q]['expense'], 0, ',', '.') }},
                        Gaji: Rp {{ number_format($summary[$q]['payroll'], 0, ',', '.') }},
                        Saldo: Rp {{ number_format($summary[$q]['balance'], 0, ',', '.') }})
                    </small>
                </h4>
            </div>
            <div class="card-body p-0">
                @if($transactions->isEmpty() && $summary[$q]['payroll'] == 0)
                    <div class="p-3 text-center text-muted">Tidak ada transaksi pada kuartal ini.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Deskripsi</th>
                                    <th class="text-end">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $t)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($t->date)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($t->type === 'income')
                                                <span class="badge bg-success">Pemasukan</span>
                                            @else
                                                <span class="badge bg-danger">Pengeluaran</span>
                                            @endif
                                        </td>
                                        <td>{{ $t->description }}</td>
                                        <td class="text-end">
                                            Rp {{ number_format($t->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach

                                @if($summary[$q]['payroll'] > 0)
                                    <tr class="fw-bold bg-light">
                                        <td colspan="3" class="text-end">Total Gaji Kuartal</td>
                                        <td class="text-end">
                                            Rp {{ number_format($summary[$q]['payroll'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
