
<!-- Create service Modal -->
<div class="modal fade" id="addcustomerservice" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Create Service</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <form method="POST" action="{{ route('service.store', ['customer' => $customer->id]) }}">
                <div class="modal-body">
                    @csrf
                    <div class="form-floating mb-3 d-flex">
                        <select class="form-control" id="service" name="service" required
                            onchange="logSelectedOption(this)">
                            <option value="">Select Service</option>
                            @foreach ($pppoeprofiles as $pppoeprofile)
                                <option value="{{ $pppoeprofile->service_name }}" {{-- @if ($customer->pppoeprofile_id == $pppoeprofile->id) selected @endif --}}>
                                    {{ $pppoeprofile->service_name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="service">Select Service</label>
                    </div>

                    <select class="form-select form-select-lg mb-3 select2" name="ipaddress" id="ipaddress">
                        <option value="" disabled selected>Select an IP</option>
                        @foreach ($poolsWithIps as $poolWithIps)
                            <optgroup
                                label="{{ $poolWithIps['pool']->name . '      ' . $poolWithIps['pool']->network }}">
                                @foreach ($poolWithIps['ips'] as $ipaddress)
                                    <option value="{{ $ipaddress->ip_address }}">
                                        {{ $ipaddress->ip_address }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <div class="form-floating mb-3 d-flex">
                        <input type="number" data-identifier="service-price-input" id="service_price"
                            name="service_price" class="form-control" placeholder="" min="1">
                        <label for="service_price">Service Price</label>
                    </div>
                    <div class="form-floating mb-3 d-flex">
                        <select class="form-select" name="nas_id" id="router">
                            <option value="" disabled selected>Select Router</option>
                            @foreach ($routers as $router)
                                <option  value="{{ $router->id }}">
                                    {{ $router->shortname }}
                                </option>
                            @endforeach
                        </select>
                        <label for="router">Router</label>
                    </div>

                    <div class="form-floating mb-3 d-flex">
                        <input type="text" id="pppoe_login" name="pppoe_login" class="form-control"
                            value={{ $customer->id }} placeholder="">
                        <label for="pppoe_login">PPPoE Login</label>
                    </div>

                    <div class="form-floating mb-3 d-flex">
                        <input type="text" id="pppoe_password1" name="pppoe_password" class="form-control"
                            placeholder="" required autocomplete="off">
                        <label for="pppoe_password1" class="m2-2">PPPoE Password</label>
                        <div class="input-group-append ms-2 form-floating">
                            <button type="button" class="form-control btn btn-outline-primary"
                                data-target="pppoe_password1" onclick="generatePassword(this)">Generate</button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    // Fetch the selected pppoe data when it is selected
    function logSelectedOption(selectElement) {

        var serviceId = selectElement.value;
        if (serviceId) {
            $.ajax({
                url: '{{ route('pppoe.show') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    serviceId: serviceId
                },
                success: function(data) {
                    if (data) {
                        console.log(data.service.service_price);
                        document.querySelector('[data-identifier="service-price-input"]').value = data
                            .service.service_price;
                    }
                },
                error: function(error) {
                    console.error('Error fetching service data:', error);
                    alert('Failed to fetch service data');
                }
            });
        }
    }
</script>