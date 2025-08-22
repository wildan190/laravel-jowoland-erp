@extends('layouts.app')

@section('title', 'Upload Recommendations')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Upload Recommendations</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Upload Recommendations</h4>
    </div>

    {{-- Form Upload --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('crm.upload.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <label for="contact_id" class="form-label fw-bold">
                            <i class="fa-solid fa-user me-1"></i> Pilih Kontak
                        </label>
                        <select name="contact_id" id="contact_id"
                                class="form-select @error('contact_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kontak --</option>
                            @foreach ($contacts as $contact)
                                <option value="{{ $contact->id }}" {{ old('contact_id') == $contact->id ? 'selected' : '' }}>
                                    {{ $contact->name }} ({{ $contact->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('contact_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="file" class="form-label fw-bold">
                            <i class="fa-solid fa-file-arrow-up me-1"></i> Pilih File (PDF/DOC/DOCX)
                        </label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" id="file" required>
                        @error('file')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mt-3 mt-md-0 text-md-end">
                        <button type="submit" class="btn btn-warning">
                            <i class="fa-solid fa-upload me-1"></i> Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Form Search & Filter --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('crm.upload.index') }}" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama file...">
                </div>
                <div class="col-md-3">
                    <select name="contact_id" class="form-select">
                        <option value="">-- Semua Kontak --</option>
                        @foreach ($contacts as $contact)
                            <option value="{{ $contact->id }}" {{ request('contact_id') == $contact->id ? 'selected' : '' }}>
                                {{ $contact->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Dari tanggal">
                </div>
                <div class="col-md-2">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="Sampai tanggal">
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-secondary">
                        <i class="fa fa-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('crm.upload.index') }}" class="btn btn-light">
                        <i class="fa fa-times me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Daftar File --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <h5 class="mb-3">Daftar File yang Diupload</h5>
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Nama File</th>
                        <th>Kontak</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($uploads as $upload)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $upload->file_name }}</td>
                            <td>
                                @if ($upload->contact)
                                    {{ $upload->contact->name }} ({{ $upload->contact->email }})
                                @else
                                    Tidak diketahui
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($upload->created_at)->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#viewDoc{{ $upload->id }}">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <a href="{{ asset('storage/' . $upload->file_path) }}" class="btn btn-sm btn-success" target="_blank">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                            </td>
                        </tr>

                        {{-- Modal Preview Dokumen --}}
                        <div class="modal fade" id="viewDoc{{ $upload->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><i class="fa-solid fa-file"></i> {{ $upload->file_name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="height: 80vh;">
                                        <iframe src="{{ asset('storage/' . $upload->file_path) }}" frameborder="0" width="100%" height="100%"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4"><em>Belum ada file diupload.</em></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $uploads->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
