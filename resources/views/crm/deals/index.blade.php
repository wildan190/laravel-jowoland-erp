@extends('layouts.app')

@section('title', 'Deals')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Deals</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between mb-3">
        <h4>Daftar Deals</h4>
            <div class="mb text-end">
                <a href="{{ route('deal.create') }}" class="btn btn-warning"><i class="fa fa-plus me-1"></i> Add Deal</a>
                <a href="{{ route('deal.kanban') }}" class="btn btn-secondary"><i class="fa fa-columns me-1"></i> View
                    Kanban</a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Title</th>
                            <th>Contact</th>
                            <th>Stage</th>
                            <th>Value</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deals as $deal)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $deal->title }}</td>
                                <td>{{ $deal->contact->name ?? '-' }}</td>
                                <td>{{ $deal->stage->name ?? '-' }}</td>
                                <td>Rp {{ number_format($deal->value, 0, ',', '.') }}</td>
                                <td>{{ ucfirst($deal->status) }}</td>
                                <td>
                                    <a href="{{ route('deal.edit', $deal->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('deal.destroy', $deal->id) }}" method="POST"
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
                    title: 'Hapus deal ini?',
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
