<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h3>Add Pool</h3>
                    </div>
                    <div class="card-body p-3">
                        <form action="{{ route('pool.store') }}" method="POST">
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

                            @csrf
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" placeholder="" required>
                                <label for="name" class="form-label">Pool Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="ranges" name="ranges"
                                    value="{{ old('ranges') }}" placeholder="" required>
                                <label for="ranges" class="form-label">IP Range</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-control" id="ip_address" name="ip_address" required>
                                    <option value="">Select router</option>
                                    @foreach ($routers as $router)
                                        <option value="{{ $router->ip_address }}">{{ $router->identity }}</option>
                                    @endforeach
                                </select>
                                <label for="ip_address" class="form-label">Router</label>
                            </div>

                            <div class="d-grid gap-2 col-6 mx-auto">
                                <button type="submit" class="btn btn-primary">Add Pool</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
