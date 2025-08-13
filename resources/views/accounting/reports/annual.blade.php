@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('accounting.reports.index') }}" class="text-decoration-none text-primary">
                            Laporan Keuangan
                        </a>
                    </li>
                    <li class="breadcrumb-item active fw-semibold">Tahun {{ $year }}</li>
                </ol>
            </nav>
            <h1 class="display-6 fw-bold text-dark mb-0">
                <i class="fas fa-chart-line text-primary me-2"></i>
                Laporan Keuangan {{ $year }}
            </h1>
        </div>
        
        {{-- Year Filter --}}
        <div class="bg-white p-3 rounded-3 shadow-sm border">
            <form method="GET" class="d-flex align-items-center gap-3">
                <label class="fw-semibold text-secondary mb-0">
                    <i class="fas fa-calendar-alt me-1"></i>Pilih Tahun:
                </label>
                <select name="year" class="form-select form-select-sm border-2" onchange="this.form.submit()" style="min-width: 100px;">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    @php
        $totalYearIncome = collect($summary)->sum('income');
        $totalYearExpense = collect($summary)->sum('expense');
        $totalYearPayroll = collect($summary)->sum('payroll');
        $totalYearBalance = $totalYearIncome - ($totalYearExpense + $totalYearPayroll);
    @endphp

    {{-- Enhanced Summary Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-arrow-up text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-secondary mb-1 fw-semibold">Total Pendapatan</p>
                            <h4 class="text-success fw-bold mb-0">
                                Rp {{ number_format($totalYearIncome, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-arrow-down text-danger fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-secondary mb-1 fw-semibold">Total Pengeluaran</p>
                            <h4 class="text-danger fw-bold mb-0">
                                Rp {{ number_format($totalYearExpense, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-users text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-secondary mb-1 fw-semibold">Total Gaji</p>
                            <h4 class="text-warning fw-bold mb-0">
                                Rp {{ number_format($totalYearPayroll, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-{{ $totalYearBalance >= 0 ? 'primary' : 'secondary' }} bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-wallet text-{{ $totalYearBalance >= 0 ? 'primary' : 'secondary' }} fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-secondary mb-1 fw-semibold">Saldo Akhir Tahun</p>
                            <h4 class="text-{{ $totalYearBalance >= 0 ? 'primary' : 'secondary' }} fw-bold mb-0">
                                Rp {{ number_format($totalYearBalance, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quarterly Reports with Modern Design --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-calendar-quarter me-2"></i>
                        Detail Laporan Per Kuartal
                    </h5>
                </div>
                <div class="card-body p-0">
                    {{-- Quarterly Tabs --}}
                    <div class="border-bottom">
                        <nav class="nav nav-pills nav-fill">
                            @foreach($quarters as $q => $transactions)
                                <a class="nav-link {{ $loop->first ? 'active' : '' }} border-0 rounded-0 py-3" 
                                   id="tab-{{ Str::slug($q) }}" data-bs-toggle="pill" href="#content-{{ Str::slug($q) }}" 
                                   role="tab">
                                    <div class="text-center">
                                        <div class="fw-bold">{{ $q }}</div>
                                        <small class="text-muted">
                                            Saldo: Rp {{ number_format($summary[$q]['balance'], 0, ',', '.') }}
                                        </small>
                                    </div>
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    {{-- Tab Content --}}
                    <div class="tab-content">
                        @foreach($quarters as $q => $transactions)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                 id="content-{{ Str::slug($q) }}" role="tabpanel">
                                
                                {{-- Quarter Summary --}}
                                <div class="bg-light p-4 border-bottom">
                                    <div class="row g-3">
                                        <div class="col-md-3 text-center">
                                            <div class="text-success">
                                                <i class="fas fa-plus-circle me-1"></i>
                                                <strong>Pemasukan</strong>
                                            </div>
                                            <div class="h6 text-success mb-0">
                                                Rp {{ number_format($summary[$q]['income'], 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="text-danger">
                                                <i class="fas fa-minus-circle me-1"></i>
                                                <strong>Pengeluaran</strong>
                                            </div>
                                            <div class="h6 text-danger mb-0">
                                                Rp {{ number_format($summary[$q]['expense'], 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="text-warning">
                                                <i class="fas fa-hand-holding-usd me-1"></i>
                                                <strong>Gaji</strong>
                                            </div>
                                            <div class="h6 text-warning mb-0">
                                                Rp {{ number_format($summary[$q]['payroll'], 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="text-primary">
                                                <i class="fas fa-balance-scale me-1"></i>
                                                <strong>Saldo</strong>
                                            </div>
                                            <div class="h6 text-primary mb-0">
                                                Rp {{ number_format($summary[$q]['balance'], 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Transactions Table --}}
                                <div class="p-4">
                                    @if($transactions->isEmpty() && $summary[$q]['payroll'] == 0)
                                        <div class="text-center py-5">
                                            <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                                            <h5 class="text-muted mt-3">Tidak ada transaksi</h5>
                                            <p class="text-muted">Belum ada transaksi pada kuartal ini.</p>
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="border-0 fw-bold">
                                                            <i class="fas fa-calendar me-1"></i>Tanggal
                                                        </th>
                                                        <th class="border-0 fw-bold">
                                                            <i class="fas fa-tag me-1"></i>Jenis
                                                        </th>
                                                        <th class="border-0 fw-bold">
                                                            <i class="fas fa-file-alt me-1"></i>Deskripsi
                                                        </th>
                                                        <th class="border-0 fw-bold text-end">
                                                            <i class="fas fa-money-bill me-1"></i>Nominal
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($transactions as $t)
                                                        <tr class="border-bottom-light">
                                                            <td class="py-3">
                                                                <span class="fw-semibold">
                                                                    {{ \Carbon\Carbon::parse($t->date)->format('d M Y') }}
                                                                </span>
                                                            </td>
                                                            <td class="py-3">
                                                                @if($t->type === 'income')
                                                                    <span class="badge bg-success bg-opacity-75 px-3 py-2">
                                                                        <i class="fas fa-arrow-up me-1"></i>Pemasukan
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-danger bg-opacity-75 px-3 py-2">
                                                                        <i class="fas fa-arrow-down me-1"></i>Pengeluaran
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="py-3">
                                                                <span class="text-dark">{{ $t->description }}</span>
                                                            </td>
                                                            <td class="py-3 text-end">
                                                                <span class="fw-bold text-{{ $t->type === 'income' ? 'success' : 'danger' }}">
                                                                    {{ $t->type === 'income' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    @if($summary[$q]['payroll'] > 0)
                                                        <tr class="bg-warning bg-opacity-10 border-warning">
                                                            <td colspan="3" class="py-3 text-end fw-bold text-warning">
                                                                <i class="fas fa-users me-2"></i>Total Gaji Kuartal
                                                            </td>
                                                            <td class="py-3 text-end fw-bold text-warning">
                                                                - Rp {{ number_format($summary[$q]['payroll'], 0, ',', '.') }}
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
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Custom CSS --}}
<style>
    .hover-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .hover-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .border-bottom-light {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
    }
    
    .nav-pills .nav-link {
        border-radius: 0;
        transition: all 0.3s ease;
    }
    
    .nav-pills .nav-link:hover {
        background-color: rgba(102, 126, 234, 0.1);
    }
    
    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-bottom: 3px solid #667eea;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
    }
</style>
@endsection