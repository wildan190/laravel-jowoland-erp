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
                        <div class="col-md-8">
                            <label for="file" class="form-label fw-bold">
                                <i class="fa-solid fa-file-arrow-up me-1"></i> Pilih File (PDF/DOC/DOCX)
                            </label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" name="file"
                                id="file" required>
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

        {{-- Daftar File --}}
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <h5 class="mb-3">Daftar File yang Diupload</h5>
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama File</th>
                            <th>Diunggah Oleh</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($uploads as $upload)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $upload->file_name }}</td>
                                <td>{{ $upload->uploaded_by ?? 'System' }}</td>
                                <td>{{ $upload->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    {{-- Tombol View (modal) --}}
                                    <button type="button" class="btn btn-sm btn-info me-1" data-bs-toggle="modal"
                                        data-bs-target="#viewDoc{{ $upload->id }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    {{-- Tombol Download --}}
                                    <a href="{{ asset('storage/' . $upload->file_path) }}" class="btn btn-sm btn-success"
                                        target="_blank">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                </td>
                            </tr>

                            {{-- Modal Preview Dokumen --}}
                            <div class="modal fade" id="viewDoc{{ $upload->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="fa-solid fa-file"></i> {{ $upload->file_name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" style="height: 80vh;">
                                            <iframe src="{{ asset('storage/' . $upload->file_path) }}" frameborder="0"
                                                width="100%" height="100%">
                                            </iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <em>Belum ada file diupload.</em>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
