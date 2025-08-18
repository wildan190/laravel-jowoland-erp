<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ERP JOWOLAND</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {{-- FullCalendar CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />

    {{-- FullCalendar JS --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/jsmind@0.6.1/style/jsmind.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.3/dragula.min.css" rel="stylesheet">
    <style>
        .kanban-columns {
            display: flex;
            gap: 20px;
        }

        .kanban-column {
            flex: 1;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            min-height: 300px;
        }

        .kanban-task {
            padding: 10px;
            background: white;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .error {
            color: red;
            font-size: 0.875em;
        }
    </style>
    <style>
        jmnodes jmnode {
            background-color: #3498db;
            color: white;
        }

        jmnodes jmnode[selected] {
            background-color: #2ecc71;
        }

        jmnodes jmnode.root {
            background-color: #e74c3c;
        }

        .jsmind-container {
            border: 1px solid #ccc;
            background: #f9f9f9;
        }
    </style>

    <style>
        @media (min-width: 768px) {
            .main-content {
                margin-left: 250px;
            }
        }

        body {
            background-color: rgb(235, 234, 232);
        }

        .breadcrumb {
            background-color: rgb(235, 234, 232) !important;
        }

        .breadcrumb-item a {
            color: rgb(0, 0, 0);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: rgb(255, 96, 0);
            font-weight: 600;
        }
    </style>
</head>

<body>

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ session('error') }}'
                });
            });
        </script>
    @endif

    {{-- Konten utama --}}
    <div class="main-content p-4">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.3/dragula.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsmind@0.6.1/js/jsmind.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsmind@0.6.1/js/jsmind.draggable.js"></script>
    @yield('scripts')

</body>

</html>
