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
                    <div class="card-header pb-0">
                        <h3>{{ $nas->nasname . '-' . $nas->shortname }}</h3>
                    </div>
                    <div class="card-body p-3 m-3">
                        <form action="{{ route('router.update', ['nas' => $nas->id]) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="border row p-2 rounded-2">

                                <div class="form-floating mb-3 d-flex">
                                    <input type="text" class="form-control rounded-end-0" id="nasname"
                                        name="nasname" placeholder="" value="{{ $nas->nasname }}">
                                    <label for="nasname">Router's IP Address</label>
                                    <div class="input-group-append ms-2 form-floating">
                                        <button type="button" class="btn btn-secondary" onclick="pingIPAddress()">
                                            <div class="hstack gap-3">
                                                <div class="p-2">Ping:</div>
                                                <span id="pingResult" class="p-2 badge rounded-pill"></span>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="shortname" name="shortname"
                                        placeholder="" value={{ $nas->shortname }}>
                                    <label for="shortname">Title</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="" autocomplete="off" value={{ $nas->username }}>
                                    <label for="username">Login (API)</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="" autocomplete="new-password">
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
                                    <input type="text" class="form-control" id="radius_server_ip"
                                        name="radius_server_ip" placeholder="" autocomplete="off"
                                        value={{ $nas->radius_server_ip }}>
                                    <label for="radius_server_ip">Radius Server IP</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="secret" name="secret"
                                        placeholder="" autocomplete="off" value={{ $nas->secret }}>
                                    <label for="secret">Radius Secret</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="ip_pool" name="ip_pool">
                                        <option selected disabled value="">Choose a pool</option>
                                        @foreach ($pools as $pool)
                                            <option value="{{ $pool->network }}"
                                                {{ $pool->network == $nas->ip_pool ? 'selected' : '' }}>
                                                {{ $pool->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="mt-2 d-grid gap-2 col-6 mx-auto">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>

                            <div class="border row p-2 rounded-2 mt-2">
                                <h5>Map</h5>


                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
    function pingIPAddress() {
        var ipAddress = document.getElementById('nasname').value;

        // Send AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/ping?ip=' + ipAddress, true);

        // Response handler
        xhr.onload = function() {
            if (xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                var pingResult = document.getElementById('pingResult');

                if (response.status === true) {
                    var latency = response.latency;
                    // var latency = 210

                    // Update span text with latency
                    pingResult.textContent = latency + ' ms';

                    // Remove existing classes
                    pingResult.classList.remove('bg-danger', 'bg-warning', 'bg-success');

                    // Assign color based on latency value
                    if (latency < 100) {
                        pingResult.classList.add('bg-success');
                    } else if (latency < 200) {
                        pingResult.classList.add('bg-warning');
                    } else {
                        pingResult.classList.add('bg-danger');
                    }
                } else {
                    pingResult.textContent = 'Unreachable';
                    pingResult.classList.remove('bg-success', 'bg-warning');
                    pingResult.classList.add('bg-danger');
                }
            } else {
                alert('Error: ' + xhr.status);
            }
        };

        // Send the request
        xhr.send();
    }
    pingIPAddress();
    // setInterval(pingIPAddress, 500);
</script>
