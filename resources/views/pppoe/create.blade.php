<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h3>Create PPPoE Service</h3>
                    </div>
                    <div class="card-body p-3">
                        <form action="{{ route('pppoe.store') }}" method="POST">
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
                            <div class="form-group">
                                <label>Status:</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" value="no" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" value="yes">
                                    <label class="form-check-label">Inactive</label>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="service_name" name="service_name" placeholder="">
                                <label for="service_name">Service Name</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="service_price" name="service_price" placeholder="">
                                <label for="service_price">Service Price</label>
                            </div>

                            <div class="form-floating mb-3 d-flex">
                                <input type="number" class="form-control" id="servie_duration" name="servie_duration" placeholder="">
                                <label for="servie_duration" class="ms-2">Service Duration</label>
                                <div class="input-group-append ms-2 form-floating">
                                    <select class="form-control" id="servie_duration_unit" name="servie_duration_unit">
                                        <option value="minutes">Minute</option>
                                        <option value="hours">Hour</option>
                                        <option value="days">Day</option>
                                        <option value="weeks">Week</option>
                                        <option value="months">Month</option>
                                        <option value="years">Year</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-floating mb-3 d-flex">
                                <input type="number" class="form-control" id="rate_download" name="rate_download" placeholder="Rate Download">
                                <label for="rate_download" class="ms-2">Rate Download</label>
                                <div class="input-group-append ms-2 form-floating">
                                    <select class="form-control" id="rate_download_unit" name="rate_download_unit">
                                        <option value="k">kbps</option>
                                        <option value="M">mbps</option>
                                        <option value="G">gbps</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-floating mb-3 d-flex">
                                <input type="number" class="form-control" id="rate_upload" name="rate_upload" placeholder="Rate Upload">
                                <label for="rate_upload" class="ms-2">Rate Upload</label>
                                <div class="input-group-append ms-2 form-floating">
                                    <select class="form-control" id="rate_upload_unit" name="rate_upload_unit">
                                        <option value="k">kbps</option>
                                        <option value="M">mbps</option>
                                        <option value="G">gbps</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-floating mb-3 d-flex">
                                <input type="number" class="form-control" id="burst_rate_download" name="burst_rate_download" placeholder="Burst Rate Download">
                                <label for="burst_rate_download" class="ms-2">Burst Rate Download</label>
                                <div class="input-group-append ms-2 form-floating">
                                    <select class="form-control" id="burst_rate_download_unit" name="burst_rate_download_unit">
                                        <option value="k">kbps</option>
                                        <option value="M">mbps</option>
                                        <option value="G">gbps</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-floating mb-3 d-flex">
                                <input type="number" class="form-control" id="burst_rate_upload" name="burst_rate_upload" placeholder="Burst Rate Upload">
                                <label for="burst_rate_upload" class="ms-2">Burst Rate Upload</label>
                                <div class="input-group-append ms-2 form-floating">
                                    <select class="form-control" id="burst_rate_upload_unit" name="burst_rate_upload_unit">
                                        <option value="k">kbps</option>
                                        <option value="M">mbps</option>
                                        <option value="G">gbps</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-floating mb-3 d-flex">
                                <input type="number" class="form-control" id="threshold_download" name="threshold_download" placeholder="Threshold Download">
                                <label for="threshold_download" class="ms-2">Threshold Download</label>
                                <div class="input-group-append ms-2 form-floating">
                                    <select class="form-control" id="threshold_download_unit" name="threshold_download_unit">
                                        <option value="k">kbps</option>
                                        <option value="M">mbps</option>
                                        <option value="G">gbps</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-floating mb-3 d-flex">
                                <input type="number" class="form-control" id="threshold_upload" name="threshold_upload" placeholder="Threshold Upload">
                                <label for="threshold_upload" class="ms-2">Threshold Upload</label>
                                <div class="input-group-append ms-2 form-floating">
                                    <select class="form-control" id="threshold_upload_unit" name="threshold_upload_unit">
                                        <option value="k">kbps</option>
                                        <option value="M">mbps</option>
                                        <option value="G">gbps</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-floating mb-3 d-flex">
                                <input type="number" class="form-control" id="burst_time" name="burst_time" placeholder="Burst Time (seconds)">
                                <label for="burst_time" class="ms-2">Burst Time (seconds)</label>
                            </div>
                            <div class="form-floating mb-3 d-flex">
                                <select class="form-control" id="priority" name="priority">
                                    
                                    <option value="8">Low</option>
                                    <option value="4">Medium</option>
                                    <option value="1">High</option>
                                </select>
                                <label for="priority" class="ms-2">Priority</label>
                            </div>

                            <div class="d-grid gap-2 col-6 mx-auto">
                                <button type="submit" class="btn btn-primary">Create PPPoE Service</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('ip_address').addEventListener('change', function() {
        var ipAddress = this.value;
        var interfaceSelect = document.getElementById('interface');

        // Clear the interface dropdown
        interfaceSelect.innerHTML = '<option value="">Select Interface</option>';

        if (ipAddress) {
            fetch('{{ route('fetch.interfaces') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ip_address: ipAddress
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        data.interfaces.forEach(function(interface) {
                            var option = document.createElement('option');
                            option.value = interface.name;
                            option.textContent = interface.name;
                            interfaceSelect.appendChild(option);
                        });
                    } else {
                        alert('Failed to fetch interfaces');
                    }
                })
                .catch(error => {
                    console.error('Error fetching interfaces:', error);
                    alert('Failed to fetch interfaces');
                });
        }
    });
</script>
