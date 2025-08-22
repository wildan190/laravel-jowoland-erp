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

    {{-- Filter --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('accounting.invoices.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Nomor Invoice</label>
                    <input type="text" class="form-control" name="invoice_number" value="{{ request('invoice_number') }}" placeholder="Cari nomor invoice">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nama Proyek / Client</label>
                    <input type="text" class="form-control" name="project_name" value="{{ request('project_name') }}" placeholder="Cari proyek / client">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Jatuh Tempo</label>
                    <input type="date" class="form-control" name="due_date" value="{{ request('due_date') }}">
                </div>
                <div class="col-md-3 text-end">
                    <button type="submit" class="btn btn-warning mt-2"><i class="fas fa-search me-1"></i> Filter</button>
                    <a href="{{ route('accounting.invoices.index') }}" class="btn btn-secondary mt-2"><i class="fas fa-times me-1"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Daftar Invoice</h4>
        <a href="{{ route('accounting.invoices.create') }}" class="btn btn-warning">
            <i class="fa fa-plus me-1"></i> Tambah Invoice
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Invoice</th>
                        <th>Project</th>
                        <th>Contact</th>
                        <th>Grand Total</th>
                        <th>Due Date</th>
                        <th>Status</th>
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
                            <td>Rp{{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
                            <td>{{ $invoice->due_date }}</td>
                            <td>
                                <form action="{{ route('accounting.invoices.update-status', $invoice->id) }}"
                                    method="POST" class="status-form">
                                    @csrf
                                    @method('PATCH')

                                    <select name="is_pending"
                                        class="form-select form-select-sm status-select {{ $invoice->is_pending ? 'bg-warning text-dark' : 'bg-success text-white' }}"
                                        data-current="{{ $invoice->is_pending ? '1' : '0' }}"
                                        {{ $invoice->is_pending ? '' : 'disabled' }}>
                                        <option value="1" {{ $invoice->is_pending ? 'selected' : '' }}>Pending</option>
                                        <option value="0" {{ !$invoice->is_pending ? 'selected' : '' }}>Paid</option>
                                    </select>
                                </form>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('accounting.invoices.show', $invoice->id) }}"
                                    class="btn btn-sm btn-info me-1" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('accounting.invoices.pdf', $invoice->id) }}"
                                    class="btn btn-sm btn-danger" title="Download PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <em>Belum ada data invoice.</em>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function(e) {
            let form = this.closest('form');
            let currentVal = this.dataset.current;
            let newVal = this.value;

            if (currentVal === '1' && newVal === '0') {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah yakin ingin mengubah status menjadi Paid?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, ubah!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else {
                        this.value = currentVal;
                    }
                });
            } else {
                this.value = currentVal;
            }
        });
    });

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", confirmButtonColor: '#198754' });
    @endif

    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}", confirmButtonColor: '#dc3545' });
    @endif
</script>
@endsection
