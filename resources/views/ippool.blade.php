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
                                <input type="text" class="form-control" id="network" name="network"
                                    value="{{ old('network') }}" placeholder="" required>
                                <label for="network" class="form-label">Network IP</label>
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
