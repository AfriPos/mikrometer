<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h4>{{ $customer->name }} - {{ $customer->id }}</h4>
                    </div>
                    <div class="card-body">

                        @if (session('success'))
                            <script>
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })

                                Toast.fire({
                                    icon: 'success',
                                    title: '{{ session('success') }}'
                                })
                            </script>
                        @endif

                        @if (session('error'))
                            <script>
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })

                                Toast.fire({
                                    icon: 'error',
                                    title: '{{ session('error') }}'
                                })
                            </script>
                        @endif


                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="javascript:;"
                                    onclick="changeActive(this, 'info')">Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="javascript:;"
                                    onclick="changeActive(this, 'service')">Service</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="javascript:;"
                                    onclick="changeActive(this, 'billing')">Billing</a>
                            </li>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="javascript:;"
                                    onclick="changeActive(this, 'statistics')">Statistics</a>
                            </li>
                        </ul>
                        <div id="info" class="tab-content active">
                            <div
                                class="rounded-2 mt-2 ps-3 pe-3 pt-3 d-flex justify-content-between bg-secondary-subtle text-secondary-emphasis">
                                <div>Account balance</div>
                                <div>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Create message</a></li>
                                            <li><a class="dropdown-item" href="#">Send message</a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Tasks
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Create</a></li>
                                            <li><a class="dropdown-item" href="#">List of tasks</a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Tickets
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Create</a></li>
                                            <li><a class="dropdown-item" href="#">List of tickets</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <form class="pt-3" action="{{ route('customer.update', $customer->id) }}" method="POST">
                                @method('PUT')
                                @csrf

                                <div class="row">
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <div class="p-3 rounded-2 border">
                                                    <h5>Main information</h5>

                                                    <div class="form-floating mb-3">
                                                        <input type="text" id="portal_login" name="portal_login"
                                                            class="form-control" value="{{ $customer->portal_login }}"
                                                            placeholder="">
                                                        <label for="portal_login">Portal login</label>
                                                    </div>

                                                    <div class="form-floating mb-3 d-flex">
                                                        <input type="text" id="portal_password"
                                                            name="portal_password" class="form-control"
                                                            value="{{ $customer->portal_password }}" placeholder="">
                                                        <label for="portal_password">Portal password</label>
                                                        <div class="input-group-append ms-2 form-floating">
                                                            <button type="button"
                                                                class="form-control btn btn-outline-primary"
                                                                data-target="portal_password"
                                                                onclick="generatePassword()">Generate</button>
                                                        </div>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <select id="status" name="status" class="form-control">
                                                            <option value="new"
                                                                {{ $customer->status == 'new' ? 'selected' : '' }}>New
                                                                (Not yet connected)</option>
                                                            <option value="active"
                                                                {{ $customer->status == 'active' ? 'selected' : '' }}>
                                                                Active</option>
                                                            <option value="blocked"
                                                                {{ $customer->status == 'blocked' ? 'selected' : '' }}>
                                                                Blocked</option>
                                                            <option value="inactive"
                                                                {{ $customer->status == 'inactive' ? 'selected' : '' }}>
                                                                Inactive (Doesn't use the service)</option>
                                                        </select>
                                                        <label for="status">Status</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input type="text" id="name" name="name"
                                                            class="form-control" value="{{ $customer->name }}"
                                                            placeholder="" required>
                                                        <label for="name">Name</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input type="email" id="email" name="email"
                                                            class="form-control" value="{{ $customer->email }}"
                                                            placeholder="" required>
                                                        <label for="email">Email</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input type="text" id="phone" name="phone"
                                                            class="form-control" value="{{ $customer->phone }}"
                                                            placeholder="" required>
                                                        <label for="phone">Phone</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <select id="service_type" name="service_type"
                                                            class="form-control">
                                                            <option value="">Select Service Type</option>
                                                            <option value="recurring">Recurring</option>
                                                            <option value="prepaid">Prepaid</option>
                                                        </select>
                                                        <label for="service_type">Service Type</label>
                                                    </div>


                                                    <div class="form-floating mb-3">
                                                        <input type="email" id="billing_email" name="billing_email"
                                                            class="form-control"
                                                            value="{{ $customer->billing_email }}" placeholder="">
                                                        <label for="billing_email">Billing Email</label>
                                                    </div>


                                                    <div class="form-floating mb-3">
                                                        <input type="text" id="street" name="street"
                                                            class="form-control" value="{{ $customer->street }}"
                                                            placeholder="">
                                                        <label for="street">Street</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input type="text" id="zip_code" name="zip_code"
                                                            class="form-control" value="{{ $customer->zip_code }}"
                                                            placeholder="">
                                                        <label for="zip_code">Zip Code</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input type="text" id="city" name="city"
                                                            class="form-control" value="{{ $customer->city }}"
                                                            placeholder="">
                                                        <label for="city">City</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input type="text" id="geo_data" name="geo_data"
                                                            class="form-control" value="{{ $customer->geo_data }}"
                                                            placeholder="">
                                                        <label for="geo_data">Geo Data</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="p-3 rounded-2 border">
                                                    <h5>Map</h5>
                                                    <div id="map" style="height: 400px;"></div>
                                                    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
                                                    <link rel="stylesheet"
                                                        href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            var geoData = document.getElementById('geo_data').value;
                                                            var coordinates = geoData.split(',');
                                                            var lat = parseFloat(coordinates[0]);
                                                            var lon = parseFloat(coordinates[1]);

                                                            var map = L.map('map').setView([lat, lon], 13);

                                                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                                            }).addTo(map);

                                                            L.marker([lat, lon]).addTo(map)
                                                                .bindPopup('{{ $customer->name }}')
                                                                .openPopup();
                                                        });
                                                    </script>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <div class="p-3 rounded-2 border">
                                                    <h5>Comments</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="p-3 rounded-2 border">
                                                    <h5>Aditional Information</h5>

                                                    <div class="form-floating mb-3">
                                                        <select id="category" name="category" class="form-control">
                                                            <option value="">Select Category</option>
                                                            <option value="individual">Individual</option>
                                                            <option value="business">Business</option>
                                                        </select>
                                                        <label for="category">Category</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input type="text" id="mpesa_phone" name="mpesa_phone"
                                                            class="form-control" value="{{ $customer->mpesa_phone }}"
                                                            placeholder="">
                                                        <label for="mpesa_phone">M-Pesa Phone Number</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input type="date" id="dob" name="dob"
                                                            class="form-control" value="{{ $customer->dob }}"
                                                            placeholder="">
                                                        <label for="dob">Date of Birth</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input type="text" id="id_number" name="id_number"
                                                            class="form-control" value="{{ $customer->id_number }}"
                                                            placeholder="">
                                                        <label for="id_number">Identification Number</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 col-6 mx-auto">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div id="service" class="tab-content" style="display: none;">
                            <div class="pt-3">
                                <div class="d-flex justify-content-end">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            Add service
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li><a class="dropdown-item" type="button" href="javascript:;"
                                                    data-bs-toggle="modal" data-bs-target="#addcustomerservice">Add
                                                    internet service</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Add recuring service</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Subscription include -->
                                @include('subscription.index')

                            </div>
                        </div>
                        <div id="billing" class="tab-content" style="display: none;">
                            <div
                                class="rounded-2 mt-2 ps-3 pe-3 pt-3 d-flex justify-content-between bg-secondary-subtle text-secondary-emphasis">
                                <div>Account balance</div>
                                <div>
                                    {{-- <div class="btn-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1"><i
                                                    class="fa-solid fa-magnifying-glass"></i></span>
                                            <input type="text" class="form-control" placeholder="Search"
                                                aria-label="Username" aria-describedby="basic-addon1">
                                        </div>
                                    </div> --}}
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Types
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">Payments</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Recurring invoice</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:;">One time invoice</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Credit note</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Future items</a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Add Document
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">One time invoice</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Recurring invoice</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:;">Credit note</a></li>
                                            <li><a class="dropdown-item" href="javascript:;" data-bs-toggle="modal"
                                                    data-bs-target="#createpayment">Payments</a></li>
                                            <li><a class="dropdown-item" href="javascript:;">Future items</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-3">
                                <!-- Payments include -->
                                @include('finance.index')
                            </div>
                            {{-- BANDWIDTH CHART AND STATISTICS --}}
                            <div id="statistics" class="tab-content statistics" style="display: none;">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>Internet</div>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 d-flex align-items-center">
                                                    <label for="serviceSelect" class="me-2 mb-0">Services:</label>
                                                    <select id="serviceSelect" class="form-select"
                                                        aria-label="Select service">
                                                        @foreach ($subscriptions as $subscription)
                                                            <option value="{{ $subscription->pppoe_login }}"
                                                                {{ $loop->first ? 'selected' : '' }}>
                                                                {{ $subscription->service }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="me-3 d-flex align-items-center">
                                                    <label for="dateRangeSelect"
                                                        class="me-2 mb-0"><b>Period:</b></label>
                                                    <div class="input-group">
                                                        <input type="text" id="dateRangeSelect"
                                                            class="form-control" style="width: 250px;" readonly>
                                                    </div>
                                                </div>
                                                <div class="me-3 d-flex align-items-center">
                                                    <button id="refreshButton" class="btn btn-outline-secondary">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        $(function() {
                                            var start = moment().startOf('month');
                                            var end = moment().endOf('month');

                                            function cb(start, end) {
                                                $('#dateRangeSelect').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                                            }

                                            $('#dateRangeSelect').daterangepicker({
                                                startDate: start,
                                                endDate: end,
                                                ranges: {
                                                    'Today': [moment(), moment()],
                                                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                                                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                                                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                                                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                                                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                                                        'month').endOf('month')]
                                                }
                                            }, cb);

                                            cb(start, end);

                                            // Add event listener for date range change
                                            $('#dateRangeSelect').on('apply.daterangepicker', function(ev, picker) {
                                                // Call your function to update data based on the selected date range
                                                updateDataBasedOnDateRange(picker.startDate, picker.endDate);
                                                loadEndedSessions(picker.startDate, picker.endDate);
                                                updateDailyBandwidthChart(picker.startDate, picker.endDate);
                                            });

                                            // Add event listener for refresh button
                                            $('#refreshButton').on('click', function() {
                                                var picker = $('#dateRangeSelect').data('daterangepicker');
                                                updateDataBasedOnDateRange(picker.startDate, picker.endDate);
                                                loadEndedSessions(picker.startDate, picker.endDate);
                                                updateDailyBandwidthChart(picker.startDate, picker.endDate);
                                            });

                                            // Call the main function on page load
                                            updateDataBasedOnDateRange(start, end);
                                            loadEndedSessions(start, end);
                                            updateDailyBandwidthChart(start, end);
                                        });

                                        // Function to update data based on selected date range
                                        function updateDataBasedOnDateRange(startDate, endDate) {
                                            var selectedService = $('#serviceSelect').val();
                                            // Fetch data using getDataTotals method from radacctController
                                            $.ajax({
                                                url: '/admin/data-totals/' + selectedService + '/' + startDate.format('YYYY-MM-DD HH:mm:ss') + '/' +
                                                    endDate.format('YYYY-MM-DD HH:mm:ss'),
                                                method: 'GET',
                                                success: function(response) {
                                                    // Update UI with the new data
                                                    $('#total-download').text(formatBytes(response.total_download));
                                                    $('#total-upload').text(formatBytes(response.total_upload));
                                                    $('#total-uptime').text(formatUptime(response.total_uptime));
                                                    $('#total-sessions').text(response.total_sessions);

                                                    // // Update daily usage graph
                                                    // updateDailyUsageGraph(response.daily_usage);
                                                },
                                                error: function(error) {
                                                    console.error('Error fetching data:', error);
                                                }
                                            });
                                        }

                                        function formatBytes(bytes) {
                                            if (bytes === 0) return '0 Bytes';
                                            const k = 1024;
                                            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                                            const i = Math.floor(Math.log(bytes) / Math.log(k));
                                            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                                        }

                                        function formatUptime(seconds) {
                                            const days = Math.floor(seconds / 86400);
                                            const hours = Math.floor((seconds % 86400) / 3600);
                                            const minutes = Math.floor((seconds % 3600) / 60);
                                            return days + 'd ' + hours + 'h ' + minutes + 'm';
                                        }
                                    </script>

                                    <div id="activeSessionContainer">
                                        <!-- Active session data will be loaded here -->
                                    </div>

                                    <div class="flex justify-content-between">
                                        <div class="card-header">Live Bandwidth Usage</div>
                                        <div>
                                            <div class="d-flex justify-content-between">
                                                <select id="subscription" class="form-select me-2"
                                                    aria-label="Select subscription">
                                                    @foreach ($subscriptions as $subscription)
                                                        <option value="{{ $subscription->id }}"
                                                            {{ $loop->first ? 'selected' : '' }}>
                                                            {{ $subscription->service }} #{{ $subscription->id }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <select id="timeFrameSelect" class="form-select">
                                                    <option value="60000">Last 1 Minute</option>
                                                    <option value="300000">Last 5 Minutes</option>
                                                    <option value="600000">Last 10 Minutes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <button id="refreshButton" onclick="refreshRealtimeChart()">Refresh</button>
                                        <canvas id="bandwidthChart" width="400" height="80"></canvas>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div id="dataDisplay" class="mt-3">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="2">Total for Period</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Total Sessions:</td>
                                                                    <td><span id="total-sessions"></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Total Download:</td>
                                                                    <td><span id="total-download"></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Total Upload:</td>
                                                                    <td><span id="total-upload"></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Total Uptime:</td>
                                                                    <td><span id="total-uptime"></span></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-md-9 d-flex flex-column">
                                                    <div class="d-flex justify-content-end mb-3">
                                                        <label for="bandwidthPeriod"
                                                            class="me-2 align-self-center">Select Period:</label>
                                                        <select id="bandwidthPeriod" class="form-select"
                                                            style="width: auto;">
                                                            <option value="hourly">Hourly</option>
                                                            <option value="daily">Daily</option>
                                                            <option value="weekly">Weekly</option>
                                                            <option value="monthly">Monthly</option>
                                                            <option value="yearly">Yearly</option>
                                                        </select>
                                                    </div>
                                                    <div class="flex-grow-1" style="height: 200px;">
                                                        <canvas id="bandwidthAverageChart"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        let bandwidthAverageChart;

                                        function updateBandwidthChart(period) {
                                            const username = $('#serviceSelect').val();
                                            fetch(`/bandwidth/average/${username}`)
                                                .then(response => response.json())
                                                .then(data => {
                                                    const ctx = document.getElementById('bandwidthAverageChart').getContext('2d');

                                                    if (bandwidthAverageChart) {
                                                        bandwidthAverageChart.destroy();
                                                    }

                                                    const chartData = data[period];
                                                    const labels = chartData.map(item => item[Object.keys(item)[0]]);
                                                    const downloadValues = chartData.map(item => item.average_upload);
                                                    const uploadValues = chartData.map(item => item.average_download);

                                                    bandwidthAverageChart = new Chart(ctx, {
                                                        type: 'line',
                                                        data: {
                                                            labels: labels,
                                                            datasets: [{
                                                                    label: 'Average Download',
                                                                    data: downloadValues,
                                                                    borderColor: 'rgb(75, 192, 192)',
                                                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                                    fill: true,
                                                                    tension: 0.4,
                                                                    cubicInterpolationMode: 'monotone'
                                                                },
                                                                {
                                                                    label: 'Average Upload',
                                                                    data: uploadValues,
                                                                    borderColor: 'rgb(255, 99, 132)',
                                                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                                                    fill: true,
                                                                    tension: 0.4,
                                                                    cubicInterpolationMode: 'monotone'
                                                                }
                                                            ]
                                                        },
                                                        options: {
                                                            responsive: true,
                                                            maintainAspectRatio: false,
                                                            animation: {
                                                                duration: 0
                                                            },
                                                            scales: {
                                                                y: {
                                                                    beginAtZero: true,
                                                                    title: {
                                                                        display: true,
                                                                        text: 'Bandwidth'
                                                                    },
                                                                    ticks: {
                                                                        callback: function(value) {
                                                                            return formatBandwidth(value);
                                                                        }
                                                                    }
                                                                }
                                                            },
                                                            plugins: {
                                                                tooltip: {
                                                                    callbacks: {
                                                                        label: function(context) {
                                                                            let label = context.dataset.label || '';
                                                                            if (label) {
                                                                                label += ': ';
                                                                            }
                                                                            label += formatBandwidth(context.parsed.y);
                                                                            return label;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    });
                                                });
                                        }

                                        function formatBandwidth(bytes) {
                                            const units = ['bps', 'Kbps', 'Mbps', 'Gbps', 'Tbps'];
                                            let value = bytes * 8; // Convert bytes/s to bits/s
                                            let unitIndex = 0;
                                            while (value >= 1024 && unitIndex < units.length - 1) {
                                                value /= 1024;
                                                unitIndex++;
                                            }
                                            return value.toFixed(2) + ' ' + units[unitIndex];
                                        }

                                        document.getElementById('bandwidthPeriod').addEventListener('change', function() {
                                            updateBandwidthChart(this.value);
                                        });

                                        // Initial chart load
                                        updateBandwidthChart('hourly');
                                    </script>
                                    <div class="mt-4">
                                        <canvas id="dailyBandwidthChart"></canvas>
                                    </div>

                                    <script>
                                        function updateDailyBandwidthChart(start,end) {
                                            const username = $('#serviceSelect').val();
                                            fetch(`/bandwidth/daily?username=${username}&start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`).then(response =>
                                                    response.json())
                                                .then(data => {
                                                    // Ensure the last record is the end date
                                                    const endDate = end.format('YYYY-MM-DD');
                                                    if (data.length === 0 || data[data.length - 1].date !== endDate) {
                                                        data.push({
                                                            date: endDate,
                                                            total_download: 0,
                                                            total_upload: 0
                                                        });
                                                    }

                                                    const ctx = document.getElementById('dailyBandwidthChart').getContext('2d');
                                                    new Chart(ctx, {
                                                        type: 'bar',
                                                        data: {
                                                            labels: data.map(item => item.date),
                                                            datasets: [{
                                                                    label: 'Download',
                                                                    data: data.map(item => item.total_upload),
                                                                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                                                    borderColor: 'rgba(75, 192, 192, 1)',
                                                                    borderWidth: 1
                                                                },
                                                                {
                                                                    label: 'Upload',
                                                                    data: data.map(item => item.total_download),
                                                                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                                                    borderColor: 'rgba(255, 99, 132, 1)',
                                                                    borderWidth: 1
                                                                }
                                                            ]
                                                        },
                                                        options: {
                                                            responsive: true,
                                                            scales: {
                                                                x: {
                                                                    stacked: true,
                                                                    title: {
                                                                        display: true,
                                                                        text: 'Date'
                                                                    }
                                                                },
                                                                y: {
                                                                    stacked: true,
                                                                    beginAtZero: true,
                                                                    title: {
                                                                        display: true,
                                                                        text: 'Bandwidth'
                                                                    },
                                                                    ticks: {
                                                                        callback: function(value) {
                                                                            return formatBytes(value);
                                                                        }
                                                                    }
                                                                }
                                                            },
                                                            plugins: {
                                                                tooltip: {
                                                                    callbacks: {
                                                                        label: function(context) {
                                                                            let label = context.dataset.label || '';
                                                                            if (label) {
                                                                                label += ': ';
                                                                            }
                                                                            label += formatBytes(context.parsed.y);
                                                                            return label;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    });
                                                });
                                        }
                                    </script>

                                    <div class="mt-4">
                                        <table id="endedSessionsTable" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Start Time</th>
                                                    <th>Stop Time</th>
                                                    <th>Duration</th>
                                                    <th>Download</th>
                                                    <th>Upload</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Ended sessions will be populated here dynamically -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- END OF BANDWIDTH CHART AND STATISTICS --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Subscription create modal include -->
@include('subscription.create')
<!-- payment create modal include -->
@include('finance.payments.create')
<script>
    function changeActive(element, tabId) {
        // Remove active class from all nav links
        var navLinks = document.getElementsByClassName("nav-link");
        for (var i = 0; i < navLinks.length; i++) {
            navLinks[i].classList.remove("active");
        }
        // Add active class to the clicked nav link
        element.classList.add("active");

        // Hide all tab contents
        var tabContents = document.getElementsByClassName("tab-content");
        for (var i = 0; i < tabContents.length; i++) {
            tabContents[i].classList.remove("active");
            tabContents[i].style.display = "none"; // Add this line to hide the tab content
        }
        // Show the corresponding tab content
        document.getElementById(tabId).classList.add("active");
        document.getElementById(tabId).style.display = "block"; // Add this line to show the tab content

        // Save the active tab ID in local storage
        localStorage.setItem('activeTab', tabId);

        if (tabId === 'statistics') {
            // setupRealtimeChart();
            refreshRealtimeChart();
            loadActiveSessions();
        } else {
            // Close existing SSE connection if open
            if (eventSource) {
                eventSource.close();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', (event) => {
        // Retrieve the active tab ID from local storage
        var activeTab = localStorage.getItem('activeTab');

        if (activeTab) {
            // Find the corresponding nav link and tab content
            var navLinks = document.getElementsByClassName("nav-link");
            var tabContents = document.getElementsByClassName("tab-content");

            for (var i = 0; i < navLinks.length; i++) {
                var onclickAttr = navLinks[i].getAttribute('onclick');
                if (onclickAttr && onclickAttr.includes(activeTab)) {
                    navLinks[i].classList.add("active");
                } else {
                    navLinks[i].classList.remove("active");
                }
            }

            for (var i = 0; i < tabContents.length; i++) {
                if (tabContents[i].id === activeTab) {
                    tabContents[i].classList.add("active");
                    tabContents[i].style.display = "block"; // Show the tab content
                } else {
                    tabContents[i].classList.remove("active");
                    tabContents[i].style.display = "none"; // Hide the tab content
                }
            }

            // Check if active tab is statistics
            if (activeTab === 'statistics') {
                setupRealtimeChart();
            } else {
                // Close existing SSE connection if open
                if (eventSource) {
                    eventSource.close();
                }
            }
        }
    });


    let eventSource = null;
    let rxData = [];
    let txData = [];
    let streamingDuration = 60000; // Default streaming duration is 1 minute
    const subscriptionSelect = document.getElementById('subscription');
    let subscriptionId = subscriptionSelect.value;
    subscriptionSelect.addEventListener('change', function() {
        subscriptionId = this.value;
        // Clear existing data arrays
        rxData = [];
        txData = [];

        // Clear existing chart data
        bandwidthChart.data.datasets[0].data = rxData;
        bandwidthChart.data.datasets[1].data = txData;

        // Update the chart
        bandwidthChart.update('none'); // Use 'none' to skip animation

        // Re-setup SSE connection
        setupRealtimeChart();
    });
    const config = {
        type: 'line',
        data: {
            datasets: [{
                label: 'Upload',
                backgroundColor: 'rgba(255, 121, 149, 0.5)',
                borderColor: 'rgb(255, 121, 149)',
                data: rxData,
                fill: true,
                tension: 0.4
            }, {
                label: 'Download',
                backgroundColor: 'rgba(82, 175, 238, 0.5)',
                borderColor: 'rgb(82, 175, 238)',
                data: txData,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                streaming: {
                    duration: streamingDuration, // Display data within selected time frame
                    refresh: 1000, // Refresh chart every 1 second
                    delay: 2000, // Delay in milliseconds before data updates
                    frameRate: 30, // Number of frames per second
                    pause: false, // Do not pause chart updates
                    ttl: undefined, // Data point lifespan (null means always show)
                }
            },
            scales: {
                x: {
                    type: 'realtime', // Use 'realtime' for x-axis
                    time: {
                        unit: 'second', // Display time in seconds
                        displayFormats: {
                            second: 'HH:mm:ss'
                        },
                        tooltipFormat: 'HH:mm:ss' // Format for tooltips
                    },
                    ticks: {
                        major: {
                            enabled: true,
                            fontStyle: 'bold'
                        },
                        autoSkip: true,
                        maxTicksLimit: 10,
                        // Rotate labels at 45 degrees angle
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                    },
                    ticks: {
                        // Format y-axis labels dynamically
                        callback: function(value) {
                            if (value >= 1e9) {
                                return (value / 1e9) + ' Gbps';
                            } else if (value >= 1e6) {
                                return (value / 1e6) + ' Mbps';
                            } else if (value >= 1e3) {
                                return (value / 1e3) + ' Kbps';
                            } else {
                                return value + ' bps';
                            }
                        }
                    }
                }
            },
            animation: {
                duration: 0 // Disable animations for smoother updates
            }
        }
    };

    // Create a new Chart instance
    const ctx = document.getElementById('bandwidthChart').getContext('2d');
    const bandwidthChart = new Chart(ctx, config);

    // Function to setup SSE connection and handle reconnections
    function setupRealtimeChart() {
        // Close existing SSE connection if open
        if (eventSource) {
            eventSource.close();
        }

        // Initialize SSE connection
        // eventSource = new EventSource('/sse');
        eventSource = new EventSource(`/sse?subscription_id=${subscriptionId}`);

        // SSE error handling and reconnect logic
        eventSource.onerror = function(error) {
            console.error('SSE Error:', error);
            // Attempt to reconnect after 3 seconds
            setTimeout(() => {
                setupRealtimeChart(); // Re-establish SSE connection
            }, 3000);
        };

        // EventSource event handler for receiving data
        eventSource.onmessage = function(event) {
            const data = JSON.parse(event.data);

            // Add new data point
            const newTime = new Date().getTime(); // Use milliseconds for timestamp

            // Push new data to the datasets
            rxData.push({
                x: newTime,
                y: data.rxRate
            });
            txData.push({
                x: newTime,
                y: data.txRate
            });

            // Limit the data arrays to show only data within the selected time frame
            const cutoff = newTime - streamingDuration; // Calculate cutoff based on selected duration
            removeOldData(rxData, cutoff);
            removeOldData(txData, cutoff);

            // Update the chart
            bandwidthChart.update('none'); // Use 'none' to skip animation
        };

        // Function to remove old data from arrays
        function removeOldData(dataArray, cutoff) {
            while (dataArray.length > 0 && dataArray[0].x < cutoff) {
                dataArray.shift();
            }
        }
    }

    // Refresh button click event listener
    function refreshRealtimeChart() {
        // Clear existing data arrays
        rxData = [];
        txData = [];

        // Clear existing chart data
        bandwidthChart.data.datasets[0].data = rxData;
        bandwidthChart.data.datasets[1].data = txData;

        // Update the chart
        bandwidthChart.update('none'); // Use 'none' to skip animation

        // Re-setup SSE connection
        setupRealtimeChart();
    }

    // Stop SSE and clear data on page refresh or unload
    window.addEventListener('beforeunload', function() {
        if (eventSource) {
            eventSource.close();
        }
        rxData = [];
        txData = [];
        bandwidthChart.data.datasets[0].data = rxData;
        bandwidthChart.data.datasets[1].data = txData;
        bandwidthChart.update('none');
    });

    // Setup SSE and start streaming with default time frame
    setupRealtimeChart();

    // Event listener for time frame selection change
    document.getElementById('timeFrameSelect').addEventListener('change', function() {
        // Update streaming duration based on selected option
        streamingDuration = parseInt(this.value);

        // Update streaming duration in chart options
        bandwidthChart.options.plugins.streaming.duration = streamingDuration;

        // Re-setup SSE connection with updated streaming duration
        setupRealtimeChart();
    });

    // Finding active sessions
    function loadActiveSessions() {
        const formatBytes = (bytes) => {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        };

        const secondsToHHMMSS = (seconds) => {
            return new Date(seconds * 1000).toISOString().substr(11, 8);
        };

        fetch('/admin/active-session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                customer_id: '{{ $customer->id }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('activeSessionContainer');
            if (data.success) {
                const sessions = data.active_sessions;
                const tableRows = sessions.map(session => `
                    <tr>
                        <td>${session.username}</td>
                        <td>${formatBytes(session.acctinputoctets || 0)}</td>
                        <td>${formatBytes(session.acctoutputoctets || 0)}</td>
                        <td>${session.acctstarttime}</td>
                        <td>${secondsToHHMMSS(session.acctsessiontime || 0)}</td>
                        <td>${session.framedipaddress}</td>
                        <td>${session.nasipaddress}</td>
                    </tr>
                `).join('');

                container.innerHTML = `
                    <div class="card mb-3">
                        <div class="card-header">Active Sessions</div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Login</th>
                                        <th>In</th>
                                        <th>Out</th>
                                        <th>Start at</th>
                                        <th>Duration</th>
                                        <th>IP</th>
                                        <th>NAS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${tableRows}
                                </tbody>
                            </table>
                        </div>
                    </div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('activeSessionContainer').innerHTML = '<p>Error loading active sessions data.</p>';
        });
    }

    // Load active sessions data when the page loads
    document.addEventListener('DOMContentLoaded', loadActiveSessions);
    // Generate an 8 character password
    function generatePassword(button) {
        var length = 8;
        var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        var password = "";
        for (var i = 0, n = charset.length; i < length; ++i) {
            password += charset.charAt(Math.floor(Math.random() * n));
        }

        // Get the id of the target input field from the button's data-target attribute
        var targetInputId = button.getAttribute('data-target');
        var targetInput = document.getElementById(targetInputId);

        // Set the generated password to the target input field
        if (targetInput) {
            targetInput.value = password;
        }
    }

    // fetch the subscription data
    function fetchSubscription(subscriptionid) {
        fetch('{{ route('service.show') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    subscriptionid: subscriptionid
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(data.subscription.pppoe_password);


                    document.querySelector('.edit_pppoe_password').value = data.subscription.pppoe_password;
                    document.querySelector('.edit_service_price').value = data.subscription
                        .service_price; // document.getElementById('serviceippool').value = data.network;
                } else {
                    alert('Failed to fetch service data');
                }
            })
            .catch(error => {
                console.error('Error fetching  service data:', error);
                alert('Failed to fetch  service data');
            });
    }

    function confirmDelete(event, route) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this service!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create a form to submit the DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = route;

                // Add CSRF token input
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                // Add DELETE method input
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function loadEndedSessions(startDate, endDate) {
        const username = $('#serviceSelect').val();
        fetch(
                `/admin/ended-sessions/${username}?start_date=${startDate.format('YYYY-MM-DD')}&end_date=${endDate.format('YYYY-MM-DD')}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const tableBody = document.querySelector('#endedSessionsTable tbody');
                    tableBody.innerHTML = '';
                    data.data.forEach(session => {
                        const row = `
                                                                <tr>
                                                                    <td>${session.radacctid}</td>
                                                                    <td>${new Date(session.acctstarttime).toLocaleString()}</td>
                                                                    <td>${new Date(session.acctstoptime).toLocaleString()}</td>
                                                                    <td>${formatDuration(session.acctsessiontime)}</td>
                                                                      <td>${formatBytes(session.acctoutputoctets)}</td>
                                                                    <td>${formatBytes(session.acctinputoctets)}</td>
                                                                </tr>
                                                            `;
                        tableBody.innerHTML += row;
                    });
                } else {
                    console.error('No ended sessions found');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function formatDuration(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const remainingSeconds = seconds % 60;
        return `${hours}h ${minutes}m ${remainingSeconds}s`;
    }

    function formatBytes(bytes) {
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes === 0) return '0 Byte';
        const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }
</script>
