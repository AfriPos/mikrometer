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
                                    onclick="changeActive(this, 'tab3')">Tab 3</a>
                            </li>
                        </ul>
                        <div id="info" class="tab-content active">
                            <form class="pt-3" action="{{ route('customer.update', $customer->id) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="text" id="name" name="name" class="form-control"
                                        value="{{ $customer->name }}" placeholder="" required>
                                    <label for="name">Name</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="email" id="email" name="email" class="form-control"
                                        value="{{ $customer->email }}" placeholder="" required>
                                    <label for="email">Email</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        value="{{ $customer->phone }}" placeholder="" required>
                                    <label for="phone">Phone</label>
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

                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <th>Status</th>
                                            <th>Profile</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Invoiced Until</th>
                                            <th>IP Address</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($services as $service)
                                                <tr>
                                                    <td>
                                                        <span
                                                            class="badge {{ $service->status == 'active' ? 'badge-sm bg-gradient-success' : 'badge-sm bg-gradient-secondary' }}">{{ $service->status }}</span>
                                                    </td>
                                                    <td>{{ $service->profile_name }}</td>
                                                    <td>{{ $service->start_date }}</td>
                                                    <td>{{ $service->end_date }}</td>
                                                    <td>{{ $service->invoiced_till }}</td>
                                                    <td>{{ $ipaddress->ip_address }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group"
                                                            aria-label="Basic example">
                                                            <a class="btn badge-sm bg-gradient-secondary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editcustomerservice">Edit</a>
                                                            <a class="btn badge-sm bg-gradient-secondary"
                                                                href="/service/{{ $service->id }}">Delete</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div id="tab3" class="tab-content" style="display: none;">
                            <h3>Content for Tab 3</h3>
                            <p>This is the content for the third tab.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>

<!-- Create service Modal -->
<div class="modal fade" id="addcustomerservice" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Service</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <form method="POST" action="{{ route('service.store', ['customer' => $customer->id]) }}">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="service">Select Plan</label>
                        <select class="form-control" id="service" name="service" required>
                            <option value="">Select Service</option>
                            @foreach ($pppoeprofiles as $pppoeprofile)
                                <option value="{{ $pppoeprofile->id }}">
                                    {{ $pppoeprofile->profile_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pppoe_login">PPPoE Login</label>
                        <input type="text" id="pppoe_login" name="pppoe_login" class="form-control"
                            value={{ $customer->id }}>
                    </div>

                    <div class="form-floating mb-3 d-flex">
                        <input type="text" id="pppoe_password" name="pppoe_password" class="form-control"
                            placeholder="" required autocomplete="off">
                        <label for="pppoe_password" class="m2-2">PPPoE Password</label>
                        <div class="input-group-append ms-2 form-floating">
                            <button type="button" class="form-control btn btn-outline-primary"
                                onclick="generatePassword()">Generate</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit service Modal -->
<div class="modal fade" id="editcustomerservice" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Service</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('service.store', ['customer' => $customer->id]) }}">
                    @csrf
                    <div class="form-group">
                        <label for="service">Select Plan</label>
                        <select class="form-control" id="service" name="service" required>
                            <option value="">Select Service</option>
                            @foreach ($pppoeprofiles as $pppoeprofile)
                                <option value="{{ $pppoeprofile->profile_name }}">
                                    {{ $pppoeprofile->profile_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pppoe_login">PPPoE Login</label>
                        <input type="text" id="pppoe_login" name="pppoe_login" class="form-control"
                            value={{ $customer->id }}>
                    </div>

                    <div class="form-floating mb-3 d-flex">
                        <input type="text" id="pppoe_password" name="pppoe_password" class="form-control"
                            placeholder="" required>
                        <label for="pppoe_password" class="m2-2">PPPoE Password</label>
                        <div class="input-group-append ms-2 form-floating">
                            <button type="button" class="form-control btn btn-outline-primary"
                                onclick="generatePassword()">Generate</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary" href="/service/{{ $service->id }}/edit">Create</button> --}}
            </div>

        </div>
    </div>
</div>

<script>
    function generatePassword() {
        var length = 8;
        var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        var password = "";
        for (var i = 0, n = charset.length; i < length; ++i) {
            password += charset.charAt(Math.floor(Math.random() * n));
        }
        document.getElementById("pppoe_password").value = password;
    }


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
</script>
