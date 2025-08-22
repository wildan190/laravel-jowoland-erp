@extends('layouts.app')

@section('title', 'Ads Plans')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ads Plans</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Daftar Ads Plans</h4>
        <a href="{{ route('crm.ads_plans.create') }}" class="btn btn-warning">
            <i class="fa fa-plus me-1"></i> Buat Ads Plan
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Objective</th>
                        <th>Budget</th>
                        <th>Periode</th>
                        <th>Platform</th>
                        <th>Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($adsPlans as $plan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $plan->name }}</td>
                            <td>{{ $plan->objective }}</td>
                            <td>Rp{{ number_format($plan->budget,0,',','.') }}</td>
                            <td>{{ $plan->schedule_start }} s/d {{ $plan->schedule_end }}</td>
                            <td>{{ $plan->platform }}</td>
                            <td>{{ $plan->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('crm.ads_plans.edit', $plan) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('crm.ads_plans.destroy', $plan) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <em>Belum ada Ads Plan.</em>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Konfirmasi hapus --}}
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.querySelector('button').addEventListener('click', function(e) {
            Swal.fire({
                title: 'Hapus Ads Plan ini?',
                text: "Tindakan ini tidak bisa dibatalkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
