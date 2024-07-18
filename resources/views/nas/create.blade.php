<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h3>Add Router</h3>
                    </div>
                    <div class="card-body p-3">
                        <form action="{{ route('router.store') }}" method="POST">
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
                                <input type="text" class="form-control" id="nasname" name="nasname"
                                    placeholder="" required>
                                <label for="nasname">Router's IP</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="radius_server_ip" name="radius_server_ip"
                                    placeholder="" required>
                                <label for="radius_server_ip">Radius Server IP</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="shortname" name="shortname" placeholder=""
                                    required>
                                <label for="shortname">Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select" id="location" name="location" required>
                                    <option value="" selected disabled>Select a location</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                                <label for="location">Location</label>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="description" name="description" placeholder="" rows="3"></textarea>
                                <label for="description">Description</label>
                            </div>

                            <div class="d-grid gap-2 col-6 mx-auto">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
