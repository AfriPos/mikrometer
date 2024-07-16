<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />


    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.js"></script>

    {{-- bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    {{-- fontawesome plugin --}}
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    {{-- jquery plugin --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    {{-- select2 plugin --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- datatable plugins --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.colVis.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-streaming"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/custom.js', 'resources/css/nucleo-icons.css', 'resources/css/nucleo-svg.css', 'resources/css/soft-ui-dashboard.css', 'resources/css/soft-ui-dashboard.min.css', 'resources/js/bootstrap-notify.js', 'resources/js/bootstrap.js', 'resources/js/Chart.extension.js', 'resources/js/chartjs.min.js', 'resources/js/perfect-scrollbar.min.js', 'resources/js/resources/js/popper.min.js', 'resources/js/soft-ui-dashboard.js', 'resources/js/soft-ui-dashboard.min.js'])
</head>

<body class="g-sidenav-show bg-gray-100">
    <!-- Page NavBar -->
    @include('layouts.navigation')

    <!-- Page Content -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ps ps--active-y">

        <!-- Page Heading -->
        @include('layouts.header')
        {{ $slot }}
    </main>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }

        // initialize select2
        $('.select2').select2({
            placeholder: 'Select an option'
        });

        // initialize DataTable2
        $('.data-table1').each(function() {
            new DataTable(this, {
                layout: {
                    stateSave: true,
                    pageLength: 10
                }
            });
        });
        // initialize DataTable2
        $('.data-table2').each(function() {
            var dataTable = new DataTable(this, {
                columnDefs: [{
                    targets: -1,
                    visible: false,
                    className: 'noVis'
                }],
                buttons: [{
                        extend: 'colvis',
                        text: 'Column visibility',
                        collectionLayout: 'fixed columns',
                        columns: ':not(.noVis)',
                        postfixButtons: ['colvisRestore']
                    },
                    {
                        extend: 'collection',
                        text: 'Entries per page',
                        buttons: [{
                            extend: 'pageLength',
                            titleAttr: 'Show number of entries'
                        }]
                    }
                ],
                language: {
                    paginate: {
                        sInfo: "Showing _START_ to _END_ of _TOTAL_ entries"
                    }
                },
                dom: '<"row"<"col-md-6"B><"col-md-6"f>><"row"<"col-md-12"tr>><"row"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',
                stateSave: true,
                pageLength: 100,
                responsive: true, // Enable responsive extension
                scrollX: true, // Enable horizontal scrolling
                initComplete: function() {
                    $('.dataTables_info').addClass('align-self-center');
                }
            });
        });
    </script>
</body>

</html>
