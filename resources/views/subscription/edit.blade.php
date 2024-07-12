<!-- Edit service Modal -->
<div class="modal fade" id="editcustomerservice{{ $subscription->id }}" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Service</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <form method="POST" action="{{ route('service.update', ['subscriptionid' => $subscription->id]) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <select class="form-select" name="status" id="status">
                            <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="disabled" {{ $subscription->status == 'disabled' ? 'selected' : '' }}>
                                Disabled</option>
                            <option value="pending" {{ $subscription->status == 'pending' ? 'selected' : '' }}>
                                Pending</option>
                        </select>
                        <label for="status">Status</label>
                    </div>

                    <div class="form-floating mb-3 d-flex">
                        <input type="date" id="start_date" name="start_date" class="form-control"
                            value="{{ $subscription->start_date }}" disabled>
                        <label for="start_date">Start
                            Date</label>
                    </div>

                    <div class="form-floating mb-3 d-flex">
                        <input type="date" id="end_date" name="end_date" class="form-control"
                            value="{{ $subscription->end_date }}">
                        <label for="end_date">End Date</label>
                    </div>
                    <select class="form-select form-select-lg mb-3" name="ipaddress" id="ipaddress">
                        @if ($subscription->ipaddress)
                            <optgroup label="Current IP">
                                <option value={{ $subscription->ipaddress }} selected>
                                    {{ $subscription->ipaddress }}
                                </option>
                            @else
                                <option value="" disabled selected>Select IP</option>
                        @endif
                        @foreach ($poolsWithIps as $poolWithIps)
                            <optgroup
                                label="{{ $poolWithIps['pool']->name . '      ' . $poolWithIps['pool']->network }}">
                                @foreach ($poolWithIps['ips'] as $ipaddress)
                                    <option value="{{ $ipaddress->ip_address }}">
                                        {{ $ipaddress->ip_address }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>

                    <div class="form-floating mb-3 d-flex">
                        <input type="number" id="service_price" name="service_price"
                            class="edit_service_price form-control" placeholder=""
                            value="{{ $subscription->service_price }}">
                        <label for="service_price">Service
                            Price</label>
                    </div>
                    <div class="form-floating mb-3 d-flex">
                        <input type="text" id="pppoe_login" name="pppoe_login" class="form-control"
                            value={{ $subscription->pppoe_login }} placeholder="">
                        <label for="pppoe_login">PPPoE
                            Login</label>
                    </div>

                    <div class="form-floating mb-3 d-flex">
                        <input type="text" id="pppoe_password2" name="pppoe_password" class="form-control"
                            value="{{ $subscription->pppoe_password }}" placeholder="" required autocomplete="off">
                        <label for="pppoe_password2" class="m2-2">PPPoE
                            Password</label>
                        <div class="input-group-append ms-2 form-floating">
                            <button type="button" class="form-control btn btn-outline-primary"
                                data-target="pppoe_password2" onclick="generatePassword(this)">Generate</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>

            </form>
        </div>
    </div>
</div>
