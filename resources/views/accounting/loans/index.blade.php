@extends('layouts.app')

@section('title', 'Daftar Kredit / Hutang')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Kredit / Hutang</li>
        </ol>
    </nav>

    <div class="mb-3">
        <a href="{{ route('accounting.loans.create') }}" class="btn btn-primary">+ Tambah Hutang</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Daftar Hutang</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Vendor / Bank</th>
                        <th>Pokok</th>
                        <th>Bunga (%)</th>
                        <th>Total Hutang</th>
                        <th>Cicilan/Bulan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Akhir</th>
                        <th>Lama Cicilan</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td>{{ $loan->vendor }}</td>
                            <td>Rp{{ number_format($loan->principal, 0, ',', '.') }}</td>
                            <td>{{ $loan->interest_rate }}</td>
                            <td>Rp{{ number_format($loan->total_debt, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($loan->monthly_installment, 0, ',', '.') }}</td>
                            <td>{{ $loan->start_date ? $loan->start_date->format('d M Y') : '-' }}</td>
                            <td>{{ $loan->end_date ? $loan->end_date->format('d M Y') : '-' }}</td>
                            <td>{{ $loan->installments }} bulan</td>
                            <td>{{ $loan->description ?? '-' }}</td>
                            <td>
                                <a href="{{ route('accounting.loans.edit', $loan) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('accounting.loans.destroy', $loan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus hutang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada data hutang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
