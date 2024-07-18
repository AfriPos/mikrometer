<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card hover-effect">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title align-self-start"><i class="fa-solid fa-user-check"></i> Online Customers
                        </h5>
                        <p class="card-text mt-auto align-self-end">{{$onlinecustomers}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card hover-effect">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title align-self-start"><i class="fa-solid fa-user-plus"></i> New Customers</h5>
                        <p class="card-text mt-auto align-self-end">{{$newcustomers}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card hover-effect">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title align-self-start"><i class="fa-solid fa-ticket"></i> Tickets</h5>
                        <p class="card-text mt-auto align-self-end">Content for card 3</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card hover-effect">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title align-self-start"><i class="fa-solid fa-link-slash"></i> Devices down</h5>
                        <p class="card-text mt-auto align-self-end">Content for card 4</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="card-title mb-0"><i class="fa-solid fa-server"></i> System</h5>
                            <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse"
                                data-bs-target="#systemTable" aria-expanded="true" aria-controls="systemTable">
                                <i class="fa-solid fa-chevron-up chevron-icon"></i>
                            </button>
                        </div>
                        <div class="collapse show" id="systemTable">
                            <table class="table table-striped mb-0">
                                <tbody>
                                    <tr>
                                        <td>CPU cores</td>
                                        <td>43</td>
                                    </tr>
                                    <tr>
                                        <td>CPU usage</td>
                                        <td>16</td>
                                    </tr>
                                    <tr>
                                        <td>Memory</td>
                                        <td>10</td>
                                    </tr>
                                    <tr>
                                        <td>Online</td>
                                        <td>5</td>
                                    </tr>
                                    <tr>
                                        <td>Online last 24 hours</td>
                                        <td>8</td>
                                    </tr>
                                    <tr>
                                        <td>Blocked</td>
                                        <td>14</td>
                                    </tr>
                                    <tr>
                                        <td>Inactive</td>
                                        <td>3</td>
                                    </tr>
                                    <tr>
                                        <td>Added last month</td>
                                        <td>18</td>
                                    </tr>
                                    <tr>
                                        <td>Added last year</td>
                                        <td>18</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="card-title mb-0"><i class="fa-solid fa-users"></i> Customers</h5>
                            <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse"
                                data-bs-target="#customerTable" aria-expanded="true" aria-controls="customerTable">
                                <i class="fa-solid fa-chevron-up chevron-icon"></i>
                            </button>
                        </div>
                        <div class="collapse show" id="customerTable">
                            <table class="table table-striped mb-0">
                                <tbody>
                                    <tr>
                                        <td>Total</td>
                                        <td>{{$customers}}</td>
                                    </tr>
                                    <tr>
                                        <td>New</td>
                                        <td>{{$newcustomers}}</td>
                                    </tr>
                                    <tr>
                                        <td>Active</td>
                                        <td>{{$onlinecustomers}}</td>
                                    </tr>
                                    <tr>
                                        <td>Online</td>
                                        <td>5</td>
                                    </tr>
                                    <tr>
                                        <td>Online last 24 hours</td>
                                        <td>8</td>
                                    </tr>
                                    <tr>
                                        <td>Blocked</td>
                                        <td>14</td>
                                    </tr>
                                    <tr>
                                        <td>Inactive</td>
                                        <td>3</td>
                                    </tr>
                                    <tr>
                                        <td>Added last month</td>
                                        <td>{{$lastMonthCustomers}}</td>
                                    </tr>
                                    <tr>
                                        <td>Added last year</td>
                                        <td>{{$lastYearCustomers}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="card-title mb-0"><i class="fa-solid fa-users"></i> Customers</h5>
                            <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse"
                                data-bs-target="#customerTable" aria-expanded="true" aria-controls="customerTable">
                                <i class="fa-solid fa-chevron-up chevron-icon"></i>
                            </button>
                        </div>
                        <div class="collapse show" id="customerTable">
                            <table class="table table-striped mb-0">
                                <tbody>
                                    <tr>
                                        <td>Total</td>
                                        <td>{{$customers}}</td>
                                    </tr>
                                    <tr>
                                        <td>New</td>
                                        <td>{{$newcustomers}}</td>
                                    </tr>
                                    <tr>
                                        <td>Active</td>
                                        <td>{{$onlinecustomers}}</td>
                                    </tr>
                                    <tr>
                                        <td>Online</td>
                                        <td>5</td>
                                    </tr>
                                    <tr>
                                        <td>Online last 24 hours</td>
                                        <td>8</td>
                                    </tr>
                                    <tr>
                                        <td>Blocked</td>
                                        <td>14</td>
                                    </tr>
                                    <tr>
                                        <td>Inactive</td>
                                        <td>3</td>
                                    </tr>
                                    <tr>
                                        <td>Added last month</td>
                                        <td>{{$lastMonthCustomers}}</td>
                                    </tr>
                                    <tr>
                                        <td>Added last year</td>
                                        <td>{{$lastYearCustomers}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script></script>
