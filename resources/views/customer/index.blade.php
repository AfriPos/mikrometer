<style>
    .dataTables_wrapper .dataTables_info {
        text-align: left !important;
        /* Align info label to the left */
    }
</style>
<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h3>Customer List</h3>
                    </div>
                    <div class="card-body p-3">
                        <div class="card-body">
                            <table class="table align-items-center mb-0 data-table2 w-100">
                                <thead>
                                    <th>Status</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Billing email</th>
                                    <th>Mpesa phone</th>
                                    <th>Date Of Birth</th>
                                    <th>Identification</th>
                                    <th>Street</th>
                                    <th>Zip</th>
                                    <th>City</th>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $customer)
                                        <tr onclick="window.location='/admin/customer/{{ $customer->id }}/edit'"
                                            style="cursor: pointer;">
                                            <td>
                                                <span
                                                    class="badge bg-{{ $customer->status == 'active' ? 'success' : ($customer->status == 'new' ? 'primary' : ($customer->status == 'blocked' ? 'danger' : ($customer->status == 'inactive' ? 'dark' : ''))) }}">
                                                    {{ $customer->status }}
                                                </span>
                                            </td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $customer->email }}</td>
                                            <td>{{ $customer->phone }}</td>
                                            <td>{{ $customer->billing_email }}</td>
                                            <td>{{ $customer->mpesa_phone }}</td>
                                            <td>{{ $customer->dob }}</td>
                                            <td>{{ $customer->id_number }}</td>
                                            <td>{{ $customer->street }}</td>
                                            <td>{{ $customer->zip_code }}</td>
                                            <td>{{ $customer->ciity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
