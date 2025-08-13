@extends('layouts.app')

@section('title', 'Penggajian')

@section('content')
<div class="container">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Penggajian</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
            <span><i class="fa fa-money-bill-wave me-1"></i> Data Penggajian</span>
            <a href="{{ route('payrolls.create') }}" class="btn btn-sm btn-warning">
                <i class="fa fa-plus me-1"></i> Tambah Penggajian
            </a>
        </div>

        <div class="card-body">
            {{-- Filter Bulan --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="monthFilter" class="form-label">Filter Bulan:</label>
                    <select id="monthFilter" class="form-select">
                        <option value="">Semua Bulan</option>
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="yearFilter" class="form-label">Filter Tahun:</label>
                    <select id="yearFilter" class="form-select">
                        <option value="">Semua Tahun</option>
                        @php
                            $years = collect($payrolls)->map(function($payroll) {
                                return \Carbon\Carbon::parse($payroll->pay_date)->format('Y');
                            })->unique()->sort()->values();
                        @endphp
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button id="resetFilter" class="btn btn-secondary">
                        <i class="fas fa-refresh me-1"></i> Reset Filter
                    </button>
                </div>
            </div>

            {{-- Summary Card --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Total Data: <span id="totalRecords">{{ count($payrolls) }}</span></strong>
                            </div>
                            <div class="col-md-3">
                                <strong>Data Ditampilkan: <span id="visibleRecords">{{ count($payrolls) }}</span></strong>
                            </div>
                            <div class="col-md-6">
                                <strong>Total Gaji: <span id="totalSalary">Rp{{ number_format(collect($payrolls)->sum(function($p) { return ($p->employee?->salary ?? 0) + ($p->allowance ?? 0); }), 0, ',', '.') }}</span></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="payrollTable">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama Karyawan</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payrolls as $payroll)
                            <tr data-date="{{ \Carbon\Carbon::parse($payroll->pay_date)->format('Y-m-d') }}" 
                                data-month="{{ \Carbon\Carbon::parse($payroll->pay_date)->format('m') }}"
                                data-year="{{ \Carbon\Carbon::parse($payroll->pay_date)->format('Y') }}"
                                data-total="{{ ($payroll->employee?->salary ?? 0) + ($payroll->allowance ?? 0) }}">
                                <td class="row-number">{{ $loop->iteration }}</td>
                                <td>{{ $payroll->employee?->name ?? '-' }}</td>
                                <td>Rp{{ number_format($payroll->employee?->salary ?? 0, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($payroll->allowance ?? 0, 0, ',', '.') }}</td>
                                <td class="fw-bold text-success">Rp{{ number_format(($payroll->employee?->salary ?? 0) + ($payroll->allowance ?? 0), 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($payroll->pay_date)->format('d M Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('payrolls.edit', $payroll) }}" class="btn btn-sm btn-primary me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('payrolls.destroy', $payroll) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr id="noDataRow">
                                <td colspan="7" class="text-center">Tidak ada data penggajian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- No Data Message (Hidden by default) --}}
            <div id="noDataMessage" class="text-center py-4" style="display: none;">
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Tidak ada data penggajian untuk filter yang dipilih.
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Custom CSS --}}
<style>
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .fade-out {
        opacity: 0.3;
        transform: scale(0.95);
    }
    
    .highlight-row {
        background-color: #fff3cd !important;
        border-left: 4px solid #ffc107;
    }
    
    .alert-info {
        background-color: #e3f2fd;
        border-color: #2196f3;
        color: #0d47a1;
    }
</style>

{{-- JavaScript for filtering --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthFilter = document.getElementById('monthFilter');
        const yearFilter = document.getElementById('yearFilter');
        const resetFilter = document.getElementById('resetFilter');
        const table = document.getElementById('payrollTable');
        const tbody = table.querySelector('tbody');
        const noDataMessage = document.getElementById('noDataMessage');
        const totalRecords = document.getElementById('totalRecords');
        const visibleRecords = document.getElementById('visibleRecords');
        const totalSalary = document.getElementById('totalSalary');
        
        // Get all data rows (exclude no-data row)
        const dataRows = Array.from(tbody.querySelectorAll('tr[data-date]'));
        const totalDataCount = dataRows.length;
        
        function updateRowNumbers() {
            const visibleRows = dataRows.filter(row => row.style.display !== 'none');
            visibleRows.forEach((row, index) => {
                const numberCell = row.querySelector('.row-number');
                if (numberCell) {
                    numberCell.textContent = index + 1;
                }
            });
        }
        
        function updateSummary() {
            const visibleRows = dataRows.filter(row => row.style.display !== 'none');
            const visibleCount = visibleRows.length;
            const totalAmount = visibleRows.reduce((sum, row) => {
                return sum + parseInt(row.dataset.total || 0);
            }, 0);
            
            visibleRecords.textContent = visibleCount;
            totalSalary.textContent = 'Rp' + totalAmount.toLocaleString('id-ID');
            
            // Show/hide no data message
            if (visibleCount === 0 && totalDataCount > 0) {
                table.style.display = 'none';
                noDataMessage.style.display = 'block';
            } else {
                table.style.display = 'table';
                noDataMessage.style.display = 'none';
            }
        }
        
        function filterTable() {
            const selectedMonth = monthFilter.value;
            const selectedYear = yearFilter.value;
            
            dataRows.forEach(row => {
                const rowMonth = row.dataset.month;
                const rowYear = row.dataset.year;
                
                const monthMatch = !selectedMonth || rowMonth === selectedMonth;
                const yearMatch = !selectedYear || rowYear === selectedYear;
                
                if (monthMatch && yearMatch) {
                    row.style.display = '';
                    row.classList.remove('fade-out');
                    // Add highlight effect for filtered results
                    if (selectedMonth || selectedYear) {
                        row.classList.add('highlight-row');
                    } else {
                        row.classList.remove('highlight-row');
                    }
                } else {
                    row.classList.add('fade-out');
                    setTimeout(() => {
                        row.style.display = 'none';
                        row.classList.remove('fade-out');
                    }, 300);
                }
            });
            
            // Update row numbers and summary after a short delay
            setTimeout(() => {
                updateRowNumbers();
                updateSummary();
            }, 350);
        }
        
        function resetFilters() {
            monthFilter.value = '';
            yearFilter.value = '';
            
            dataRows.forEach(row => {
                row.style.display = '';
                row.classList.remove('highlight-row', 'fade-out');
            });
            
            updateRowNumbers();
            updateSummary();
            
            // Visual feedback
            resetFilter.innerHTML = '<i class="fas fa-check me-1"></i> Reset!';
            resetFilter.classList.add('btn-success');
            resetFilter.classList.remove('btn-secondary');
            
            setTimeout(() => {
                resetFilter.innerHTML = '<i class="fas fa-refresh me-1"></i> Reset Filter';
                resetFilter.classList.add('btn-secondary');
                resetFilter.classList.remove('btn-success');
            }, 1000);
        }
        
        // Event listeners
        monthFilter.addEventListener('change', filterTable);
        yearFilter.addEventListener('change', filterTable);
        resetFilter.addEventListener('click', resetFilters);
        
        // Auto-select current month on page load (optional)
        const currentMonth = new Date().getMonth() + 1;
        const currentYear = new Date().getFullYear();
        // Uncomment the lines below if you want to auto-filter to current month
        // monthFilter.value = currentMonth.toString().padStart(2, '0');
        // yearFilter.value = currentYear.toString();
        // filterTable();
    });

    // SweetAlert for delete confirmation
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: 'Yakin ingin menghapus data ini?',
                text: "Data yang dihapus tidak bisa dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection