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
                                            <li><a class="dropdown-item" href="javascript:;">Recurring invoice</a></li>
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
                            <div id="statistics" class="tab-content" style="display: none;">
                                <h3>Content for Tab 3</h3>
                                <p>This is the content for the statistics.</p>
                            </div>
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
        }
    });


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
</script>
