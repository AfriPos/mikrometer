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
                                <div>Account balance: <b>KSH {{ $customer->account_balance }}</b></div>
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
                                <div>Account balance: <b>KSH {{ $customer->account_balance }}</b></div>
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
                                            <li><a class="dropdown-item" href="javascript:;" data-bs-toggle="modal"
                                                    data-bs-target="#createinvoice">One time invoice</a></li>
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
                                <div class="border border-secondary-subtle mt-3 rounded-2">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5>Active sessions</h5>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 d-flex align-items-center">
                                                    <select id="serviceSelect" class="form-select"
                                                        aria-label="Select service">
                                                        @foreach ($subscriptions as $subscription)
                                                            <option value="{{ $subscription->pppoe_login }}"
                                                                {{ $loop->first ? 'selected' : '' }}>
                                                                #{{ $subscription->id }} {{ $subscription->service }}
                                                                {{ $subscription->ipaddress }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="me-3 d-flex align-items-center">
                                                    <div class="input-group">
                                                        <input type="text" id="dateRangeSelect"
                                                            class="form-control" style="width: 250px;" readonly>
                                                    </div>
                                                </div>
                                                <div class="me-3 d-flex align-items-center">
                                                    <button id="refreshButton" class="mt-3 btn btn-outline-secondary">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="activeSessionContainer">
                                        <!-- Active session data will be loaded here -->
                                    </div>
                                </div>
                                <div class="border border-secondary-subtle mt-3 rounded-2 p-1">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5>Live Bandwidth Usage</h5>
                                            <button id="remove">remove</button>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 d-flex align-items-center">
                                                    <select id="timeFrameSelect" class="form-select">
                                                        <option value="60000">Last 1 Minute</option>
                                                        <option value="300000">Last 5 Minutes</option>
                                                        <option value="600000">Last 10 Minutes</option>
                                                    </select>
                                                </div>
                                                <div class="me-3 d-flex align-items-center">
                                                    <button class="btn btn-outline-secondary mt-3" id="refreshButton"
                                                        onclick="refreshRealtimeChart()" class=""><i
                                                            class="fas fa-sync-alt"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="bandwidthChartContainer">
                                        <canvas id="bandwidthChart" width="400" height="80"></canvas>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12 col-lg-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="p-3 rounded-2 border">
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
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-9">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="p-3 rounded-2 border">
                                                    <div class="">
                                                        <div class="d-flex justify-content-end">
                                                            <label for="bandwidthPeriod"
                                                                class="me-2 align-self-center">Select
                                                                Period:</label>
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
                                    </div>
                                </div>
                                <div class="border border-secondary-subtle mt-3 rounded-2 p-1">
                                    <div class="card-header">
                                        <h5 class="card-title">Usage by Day</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="flex-grow-1 d-flex justify-content-center align-items-center"
                                            style="height: 35vh;">
                                            <div class="mt-4" id="bandwidthChartContainer"
                                                style="height: 100%; width: 100%; position: relative;">
                                                <canvas id="dailyBandwidthChart"
                                                    style="width: 100%; height: 100%;"></canvas>
                                                <div id="noDataMessage"
                                                    style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                                                    No data to
                                                    display</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="border border-secondary-subtle mt-3 rounded-2 p-2">
                                    <table id="endedSessionsTable" class="table table-striped data-table1 w-100">
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
<!-- invoice create modal include -->
@include('finance.invoices.create')
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

        // if (tabId === 'statistics') {
        //     // setupRealtimeChart();
        //     refreshRealtimeChart();
        //     loadActiveSessions();
        // } else {
        //     // Close existing SSE connection if open
        //     if (eventSource) {
        //         eventSource.close();
        //     }
        // }
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
            // if (activeTab === 'statistics') {
            //     // setupRealtimeChart();

            //     updateDataBasedOnDateRange(start, end);
            //     updateBandwidthChart("hourly")
            // } else {
            //     // Close existing SSE connection if open
            //     if (eventSource) {
            //         eventSource.close();
            //     }
            // }
        }
    });
</script>
