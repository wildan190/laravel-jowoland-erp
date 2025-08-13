@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="modern-breadcrumb mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-chart-line me-1"></i>Overview
            </li>
        </ol>
    </nav>

    {{-- Statistics Cards --}}
    <div class="row g-4 mb-5">
        @php
            $cards = [
                ['label'=>'Total Pemasukan','value'=>'Rp'.number_format($stats['total_income'],0,',','.'),'icon'=>'fa-arrow-trend-up','color'=>'success'],
                ['label'=>'Total Pengeluaran','value'=>'Rp'.number_format($stats['total_purchasing'],0,',','.'),'icon'=>'fa-arrow-trend-down','color'=>'warning'],
                ['label'=>'Total Hutang','value'=>'Rp'.number_format($stats['total_loan'],0,',','.'),'icon'=>'fa-hand-holding-dollar','color'=>'danger'],
                ['label'=>'Total Proyek','value'=>$stats['total_projects'],'icon'=>'fa-project-diagram','color'=>'primary'],
            ];
        @endphp

        @foreach($cards as $card)
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 shadow-sm rounded" onclick="animateCard(this)">
                    <div class="card-body p-4 text-dark">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="fs-2 text-{{ $card['color'] }}">
                                <i class="fas {{ $card['icon'] }}"></i>
                            </div>
                            <div class="text-end">
                                <div class="small fw-medium">{{ $card['label'] }}</div>
                                <div class="fs-4 fw-bold">{{ $card['value'] }}</div>
                            </div>
                        </div>
                        @if($card['label']=='Total Pemasukan')
                            <div class="d-flex align-items-center small text-muted">
                                <i class="fas fa-arrow-up text-success me-2"></i>+12% dari bulan lalu
                            </div>
                        @elseif($card['label']=='Total Pengeluaran')
                            <div class="d-flex align-items-center small text-muted">
                                <i class="fas fa-arrow-down text-warning me-2"></i>-8% dari bulan lalu
                            </div>
                        @elseif($card['label']=='Total Hutang')
                            <div class="small text-muted mt-2">
                                Cicilan/Bulan: <strong>Rp{{ number_format($stats['total_monthly_installment'],0,',','.') }}</strong>
                            </div>
                        @elseif($card['label']=='Total Proyek')
                            <div class="d-flex justify-content-between small text-muted mt-2">
                                <span>Selesai: {{ $stats['completed_projects'] }}</span>
                                <span>Overdue: {{ $stats['overdue_projects'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Progress Proyek --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-tasks text-primary me-2"></i>Progres Proyek Real-time
            </h5>
        </div>
        <div class="card-body">
            @if($projects->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="fas fa-folder-open fs-1 mb-3"></i>
                    <h6>Belum Ada Proyek</h6>
                    <p>Mulai proyek pertama Anda untuk melihat progress di sini</p>
                    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm rounded-pill">
                        <i class="fas fa-plus me-1"></i>Tambah Proyek Baru
                    </a>
                </div>
            @else
                @foreach($projects as $project)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $project->name }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    Deadline: {{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}
                                </small>
                            </div>
                            @if ($project->progress_percentage == 100)
                                <span class="badge bg-success">Selesai</span>
                            @elseif($project->is_overdue)
                                <span class="badge bg-danger">Overdue</span>
                            @else
                                <span class="badge bg-info">Progress</span>
                            @endif
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar @if($project->progress_percentage==100) bg-success @elseif($project->is_overdue) bg-danger @else bg-info @endif" role="progressbar" style="width: {{ $project->progress_percentage }}%" aria-valuenow="{{ $project->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        @if($project->description)
                            <p class="small text-muted mt-2 mb-0">{{ \Illuminate\Support\Str::limit($project->description, 100) }}</p>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function animateCard(card){
    card.classList.add('shadow-lg');
    setTimeout(()=>card.classList.remove('shadow-lg'),150);
}
</script>
@endpush
@endsection
