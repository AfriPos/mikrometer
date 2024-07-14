<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-streaming"></script>
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
                                    <div id="activeSessionContainer">
                                        <!-- Active session data will be loaded here -->
                                    </div>

                                    <div class="flex justify-content-between">
                                        <div class="card-header">Live Bandwidth Usage</div>
                                        <div>
                                            <div class="d-flex justify-content-between">
                                                <select id="subscription" class="form-select me-2" aria-label="Select subscription">
                                                    @foreach ($subscriptions as $subscription)
                                                        <option value="{{ $subscription->id }}" {{ $loop->first ? 'selected' : '' }}>
                                                            {{ $subscription->service }} #{{ $subscription->id }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <select id="timeFrameSelect" class="form-select">
                                                    <option value="60000">Last 1 Minute</option>
                                                    <option value="300000">Last 5 Minutes</option>
                                                    <option value="600000">Last 10 Minutes</option>
                                                </select>
                                            </div>                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <button id="refreshButton" onclick="refreshRealtimeChart()">Refresh</button>
                                        <canvas id="bandwidthChart" width="400" height="80"></canvas>
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
            loadActiveSessions();
            refreshRealtimeChart()
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
                loadActiveSessions();
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
                    let html = `
                    <div class="card mb-3">
                        <div class="card-header">Active Sessions</div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <th>Login</th>
                                    <th>In</th>
                                    <th>Out</th>
                                    <th>Start at</th>
                                    <th>Duration</th>
                                    <th>IP</th>
                                    <th>NAS</th>
                                </thead>
                                <tbody>`;
                    
                    sessions.forEach(session => {
                        html += `
                            <tr>
                                <td>${session.username}</td>
                                <td>${session.acctinputoctets ? formatBytes(session.acctinputoctets) : '0 B'}</td>
                                <td>${session.acctoutputoctets ? formatBytes(session.acctoutputoctets) : '0 B'}</td>
                                <td>${session.acctstarttime}</td>
                                <td>${session.acctsessiontime ? secondsToHHMMSS(session.acctsessiontime) : '00:00:00'}</td>
                                <td>${session.framedipaddress}</td>
                                <td>${session.nasipaddress}</td>
                            </tr>`;
                    });
                    
                    html += `
                                </tbody>
                            </table>
                        </div>
                    </div>`;
                    
                    container.innerHTML = html;
                } 
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('activeSessionsContainer').innerHTML =
                    '<p>Error loading active sessions data.</p>';
            });
    }

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
</script>
{{-- END ACTIVE SESSION SCRIPT --}}
