<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h3>Add new customer</h3>
                    </div>
                    <div class="card-body p-3">
                        <div class="card-body">

                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('customer.store') }}">
                                @csrf

                                {{-- <div class="form-floating mb-3">
                                    <input type="text" id="portal_login" name="portal_login" class="form-control"
                                        value="{{ old('name') }}" placeholder="">
                                    <label for="portal_login">Portal login</label>
                                </div> --}}

                                <div class="form-floating mb-3 d-flex">
                                    <input type="text" id="portal_password" name="portal_password"
                                        class="form-control" value="{{ old('name') }}" placeholder="">
                                    <label for="portal_password">Portal password</label>
                                    <div class="input-group-append ms-2 form-floating">
                                        <button type="button" class="form-control btn btn-outline-primary"
                                            onclick="generatePassword()">Generate</button>
                                    </div>
                                </div>

                                <div class="form-floating mb-3">
                                    <select id="service_type" name="service_type" class="form-control">
                                        <option value="">Select Service Type</option>
                                        <option value="recurring">Recurring</option>
                                        <option value="prepaid">Prepaid</option>
                                    </select>
                                    <label for="service_type">Service Type</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <select id="category" name="category" class="form-control">
                                        <option value="">Select Category</option>
                                        <option value="individual">Individual</option>
                                        <option value="business">Business</option>
                                    </select>
                                    <label for="category">Category</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" id="name" name="name" class="form-control"
                                        value="{{ old('name') }}" placeholder="">
                                    <label for="name">Name</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="email" id="email" name="email" class="form-control"
                                        value="{{ old('email') }}" placeholder="">
                                    <label for="email">Email</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="email" id="billing_email" name="billing_email" class="form-control"
                                        value="{{ old('billing_email') }}" placeholder="">
                                    <label for="billing_email">Billing Email</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" id="mpesa_phone" name="mpesa_phone" class="form-control"
                                        value="{{ old('mpesa_phone') }}" placeholder="">
                                    <label for="mpesa_phone">M-Pesa Phone Number</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="date" id="dob" name="dob" class="form-control"
                                        value="{{ old('dob') }}" placeholder="">
                                    <label for="dob">Date of Birth</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" id="id_number" name="id_number" class="form-control"
                                        value="{{ old('id_number') }}" placeholder="">
                                    <label for="id_number">Identification Number</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" id="street" name="street" class="form-control"
                                        value="{{ old('street') }}" placeholder="">
                                    <label for="street">Street</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" id="zip_code" name="zip_code" class="form-control"
                                        value="{{ old('zip_code') }}" placeholder="">
                                    <label for="zip_code">Zip Code</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" id="city" name="city" class="form-control"
                                        value="{{ old('city') }}" placeholder="">
                                    <label for="city">City</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" id="geo_data" name="geo_data" class="form-control"
                                        value="{{ old('geo_data') }}" placeholder="">
                                    <label for="geo_data">Geo Data</label>
                                </div>


                                <div class="form-floating mb-3">
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        value="{{ old('phone') }}" placeholder="">
                                    <label for="phone">Phone number</label>
                                </div>

                                <div class="d-grid gap-2 col-6 mx-auto">
                                    <button type="submit" class="btn btn-primary">Add Customer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // generate an 8 character password
    function generatePassword() {
        var length = 8;
        var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        var password = "";
        for (var i = 0, n = charset.length; i < length; ++i) {
            password += charset.charAt(Math.floor(Math.random() * n));
        }
        document.getElementById("portal_password").value = password;
    }
</script>
