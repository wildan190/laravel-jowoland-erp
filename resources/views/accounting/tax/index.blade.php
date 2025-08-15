@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-home"></i> Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Laporan Pajak & Keuangan
            </li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">
            <i class="fa-solid fa-file-invoice-dollar me-2 text-warning"></i> Laporan Pajak & Keuangan
        </h2>
    </div>

    {{-- Filter Form --}}
    <form method="GET" class="mb-4 row g-3 bg-light p-3 rounded shadow-sm border border-warning">
        <div class="col-md-4">
            <label class="fw-bold text-dark">Start Date</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="form-control border-warning">
        </div>
        <div class="col-md-4">
            <label class="fw-bold text-dark">End Date</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="form-control border-warning">
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-warning text-dark fw-bold">
                <i class="fa-solid fa-filter me-1"></i> Filter
            </button>
            <a href="{{ route('accounting.tax.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
               class="btn btn-dark fw-bold" target="_blank">
               <i class="fa-solid fa-file-pdf me-1"></i> Cetak PDF
            </a>
        </div>
    </form>

    {{-- Laba Rugi --}}
    <div class="card border-warning shadow-sm mb-4">
        <div class="card-header bg-warning text-dark fw-bold">
            <i class="fa-solid fa-chart-line me-1"></i> Laporan Laba Rugi
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered m-0">
                <tr><th>Total Pendapatan</th><td class="text-end">Rp {{ number_format($totalIncome,0,',','.') }}</td></tr>
                <tr><th>HPP</th><td class="text-end">Rp {{ number_format($hpp,0,',','.') }}</td></tr>
                <tr class="table-light"><th>Laba Kotor</th><td class="text-end fw-bold">Rp {{ number_format($labaKotor,0,',','.') }}</td></tr>
                <tr><th>Beban Usaha</th><td class="text-end">Rp {{ number_format($bebanUsaha,0,',','.') }}</td></tr>
                <tr class="table-warning"><th>Laba Bersih</th><td class="text-end fw-bold">Rp {{ number_format($labaBersih,0,',','.') }}</td></tr>
            </table>
        </div>
    </div>

    {{-- Pajak --}}
    <div class="card border-dark shadow-sm mb-4">
        <div class="card-header bg-dark text-warning fw-bold">
            <i class="fa-solid fa-receipt me-1"></i> Laporan Pajak
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered m-0">
                <tr><th>PPN (11%)</th><td class="text-end">Rp {{ number_format($ppn,0,',','.') }}</td></tr>
                <tr><th>PPh21</th><td class="text-end">Rp {{ number_format($pph21,0,',','.') }}</td></tr>
                <tr><th>PPh Badan (22%)</th><td class="text-end">Rp {{ number_format($pphBadan,0,',','.') }}</td></tr>
                <tr class="table-warning"><th>Total SPT Tahunan</th><td class="text-end fw-bold">Rp {{ number_format($totalSPT,0,',','.') }}</td></tr>
            </table>
        </div>
    </div>

    {{-- Neraca --}}
    <div class="card border-warning shadow-sm">
        <div class="card-header bg-warning text-dark fw-bold">
            <i class="fa-solid fa-scale-balanced me-1"></i> Neraca
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Aktiva --}}
                <div class="col-md-6">
                    <h5 class="fw-bold text-dark">Aktiva</h5>
                    <table class="table table-bordered">
                        <tr class="table-secondary"><th colspan="2">Aktiva Lancar</th></tr>
                        @foreach($aktivaLancar as $nama => $nilai)
                            <tr>
                                <td>{{ $nama }}</td>
                                <td class="text-end">Rp {{ number_format($nilai,0,',','.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="table-secondary"><th colspan="2">Aktiva Tetap</th></tr>
                        @foreach($aktivaTetap as $nama => $nilai)
                            <tr>
                                <td>{{ $nama }}</td>
                                <td class="text-end">Rp {{ number_format($nilai,0,',','.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="table-warning fw-bold"><th>Total Aktiva</th><th class="text-end">Rp {{ number_format($totalAktiva,0,',','.') }}</th></tr>
                    </table>
                </div>

                {{-- Pasiva --}}
                <div class="col-md-6">
                    <h5 class="fw-bold text-dark">Pasiva</h5>
                    <table class="table table-bordered">
                        <tr class="table-secondary"><th colspan="2">Kewajiban Jangka Pendek</th></tr>
                        @foreach($kewajibanPendek as $nama => $nilai)
                            <tr>
                                <td>{{ $nama }}</td>
                                <td class="text-end">Rp {{ number_format($nilai,0,',','.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="table-secondary"><th colspan="2">Kewajiban Jangka Panjang</th></tr>
                        @foreach($kewajibanPanjang as $nama => $nilai)
                            <tr>
                                <td>{{ $nama }}</td>
                                <td class="text-end">Rp {{ number_format($nilai,0,',','.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="table-secondary"><th colspan="2">Ekuitas</th></tr>
                        <tr>
                            <td>Modal & Laba Ditahan</td>
                            <td class="text-end">Rp {{ number_format($ekuitas,0,',','.') }}</td>
                        </tr>
                        <tr class="table-warning fw-bold"><th>Total Pasiva</th><th class="text-end">Rp {{ number_format($totalAktiva,0,',','.') }}</th></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
