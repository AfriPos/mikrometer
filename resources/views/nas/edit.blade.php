<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h3>{{ $nas->nasname . '-' . $nas->shortname }}</h3>
                    </div>
                    <div class="card-body p-3 m-3">
                        <form action="{{ route('router.update', ['nas' => $nas->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
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

                            <div class="border row p-2 rounded-2">

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nasname" name="nasname"
                                        placeholder="" value={{ $nas->nasname }}>
                                    <label for="nasname">NAS Address</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="shortname" name="shortname"
                                        placeholder="" value={{ $nas->shortname }}>
                                    <label for="shortname">Title</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="" value={{ $nas->username }}>
                                    <label for="username">Login (API)</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="">
                                    <label for="password">Password (API)</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3"> {{ $nas->description }} </textarea>
                                    <label for="description">Description</label>
                                </div>

                            </div>
                            <div class="border row p-2 rounded-2 mt-2">
                                <h5>Radius</h5>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="radius_server_ip" name="radius_server_ip"
                                        placeholder="" value={{ $nas->radius_server_ip }}>
                                    <label for="radius_server_ip">Radius Server IP</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="secret" name="secret"
                                        placeholder="" value={{ $nas->secret }}>
                                    <label for="secret">Radius Secret</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="pool" name="pool">
                                        <option selected disabled value="">Choose a pool</option>
                                        @foreach ($pools as $pool)
                                            <option value="{{ $pool->network }}">
                                                {{ $pool->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="mt-2 d-grid gap-2 col-6 mx-auto">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
