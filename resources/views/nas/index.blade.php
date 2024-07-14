<x-app-layout>
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
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between">
                        <h3>Routers</h3>
                        <a href="{{ route('router.create') }}" class="btn btn-primary mb-3">Add Router</a>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0 data-table1 w-100">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>IP</th>
                                        <th>Status</th>
                                        <th>Customers online</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($routers as $router)
                                        <tr>
                                            <td> {{ $router->shortname }} </td>
                                            <td> {{ $router->type }} </td>
                                            <td> {{ $router->nasname }} </td>
                                            <td>
                                                @if ($router->configured == 1)
                                                    <span class="badge bg-success">API OK</span>
                                                @else
                                                    <span class="badge bg-warning">API Unknown</span>
                                                @endif
                                            </td>
                                            <td>{{ $router->clients_count }} </td>
                                            <td>
                                                <a href="{{ route('router.edit', $router->id) }}"
                                                    class="btn btn-secondary"><i class="fa-solid fa-pencil"></i></a>
                                                <form action="{{ route('router.destroy', $router->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="deleteConfirmation(this.form)">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
                                                </form>
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

<script>
    function deleteConfirmation(form) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                form.submit();
            }
        });
    }
</script>
