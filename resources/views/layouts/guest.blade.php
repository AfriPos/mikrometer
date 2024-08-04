<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MikroMeter') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/custom.js', 'resources/css/nucleo-icons.css', 'resources/css/nucleo-svg.css', 'resources/css/soft-ui-dashboard.css', 'resources/css/soft-ui-dashboard.min.css', 'resources/js/bootstrap-notify.js', 'resources/js/bootstrap.js', 'resources/js/Chart.extension.js', 'resources/js/chartjs.min.js', 'resources/js/perfect-scrollbar.min.js', 'resources/js/resources/js/popper.min.js', 'resources/js/soft-ui-dashboard.js', 'resources/js/soft-ui-dashboard.min.js'])
</head>

<body class="bg-gray-200">
    {{ $slot }}
</body>

</html>
