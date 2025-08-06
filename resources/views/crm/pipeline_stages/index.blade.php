@extends('layouts.app')

@section('title', 'Pipeline Stages')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pipeline Stages</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between mb-3">
            <h4>Daftar Stage</h4>
            <a href="{{ route('pipeline.create') }}" class="btn btn-warning"><i class="fa fa-plus me-1"></i> Add Stage</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Stage Name</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stages as $stage)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $stage->name }}</td>
                                <td>{{ $stage->order }}</td>
                                <td>
                                    <a href="{{ route('pipeline.edit', $stage->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('pipeline.destroy', $stage->id) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus stage ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Ya, hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endsection
