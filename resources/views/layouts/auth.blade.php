<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login & Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @yield('styles')
</head>
<body class="bg-light">

    @yield('content')

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
