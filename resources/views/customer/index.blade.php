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
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <th>name</th>
                                    <th>email</th>
                                    <th>phone</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $customer)
                                        <tr>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $customer->email }}</td>
                                            <td>{{ $customer->phone }}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a class="btn badge-sm bg-gradient-secondary"
                                                        href="/admin/customer/{{ $customer->id }}/edit">Edit</a>
                                                </div>
                                            </td>

                                            </td>
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
