<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h3 class="m-0">Role: {{ $role->name }}</h3>
                    </div>
                    <div class="card-body p-3">
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

                            <div class="container">
                                <form action="{{ route('roles.editpermissions', $role->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        @foreach ($permissions as $permission)
                                            <div class="form-check col-md-3 row">
                                                <input class="form-check-input" type="checkbox"
                                                    id="permissions{{ $permission->id }}" name="permissions[]"
                                                    value="{{ $permission->name }}"
                                                    {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="permissions{{ $permission->id }}">{{ $permission->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>


                                    <div class="d-grid gap-2 col-6 mx-auto mt-3">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
