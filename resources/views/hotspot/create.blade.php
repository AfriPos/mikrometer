<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h3>Create Hotspot Plan</h3>
                    </div>
                    <div class="card-body p-3">
                        <form action="{{ route('hotspot.store') }}" method="POST">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="plan_name" name="plan_name" placeholder="">
                                <label for="plan_name">Plan Name</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="plan_price" name="plan_price" placeholder="">
                                <label for="plan_price">Plan Price</label>
                            </div>

                            <div class="form-floating mb-3 d-flex">
                                <input type="number" class="form-control" id="data_limit" name="data_limit" placeholder="">
                                <label for="data_limit" class="ms-2">Data Limit</label>
                                <div class="input-group-append ms-2 form-floating">
                                    <select class="form-control" id="data_limit_unit" name="data_limit_unit">
                                        <option value="MB">MB</option>
                                        <option value="GB">GB</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-floating mb-3 d-flex">
                                <input type="number" class="form-control" id="validity" name="validity" placeholder="">
                                <label for="validity" class="ms-2">Validity</label>
                                <div class="input-group-append ms-2 form-floating">
                                    <select class="form-control" id="validity_unit" name="validity_unit">
                                        <option value="hours">Hours</option>
                                        <option value="days">Days</option>
                                        <option value="months">Months</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-floating mb-3 d-flex">
                                <input type="number" class="form-control" id="speed_limit" name="speed_limit" placeholder="">
                                <label for="speed_limit" class="ms-2">Speed Limit</label>
                                <div class="input-group-append ms-2 form-floating">
                                    <select class="form-control" id="speed_limit_unit" name="speed_limit_unit">
                                        <option value="k">kbps</option>
                                        <option value="M">Mbps</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Simultaneous Use:</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="simultaneous_use" value="yes">
                                    <label class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="simultaneous_use" value="no" checked>
                                    <label class="form-check-label">No</label>
                                </div>
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
