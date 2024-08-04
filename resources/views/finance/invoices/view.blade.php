<!-- Create service Modal -->
<div class="modal fade" id="viewinvoice{{ $record->recordable->id }}" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">View invoice</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-3">Invoices to:</div>
                    <div class="col-3"> {{ $record->recordable->customer->name }} </div>
                    <div class="col-3">Document Date:</div>
                    <div class="col-3"> {{ $record->recordable->created_at }} </div>
                </div>
                <div class="row">
                    <div class="col-3">Number:</div>
                    <div class="col-3"> {{ $record->recordable->id }} </div>
                    <div class="col-3">Due Date:</div>
                    <div class="col-3"> {{ $record->recordable->due_date }} </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        {{-- <table class="table shadow p-1 mb-5 mt-5 bg-body-tertiary rounded-3">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>TAX %</th>
                                    <th>Subtotal</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table> --}}
                    </div>
                </div>

                <div class="row pt-3">
                    <!-- accepted payments column -->
                    <div class="col-6">
                    </div>
                    <!-- /.col -->
                    <div class="col-6">
                        <div class="row">
                            <div class="col-sm-6 text-end">
                                Total without VAT:
                            </div>
                            <div class="col-sm-6 text-end subtotal">
                                Ksh {{ $record->recordable->amount }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 text-end">
                                VAT:
                            </div>
                            <div class="col-sm-6 text-end vat">

                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-6 text-end">
                                <strong class="fs-5">Total:</strong> <!-- Add fs-5 class for bigger text -->
                            </div>
                            <div class="col-sm-6 text-end">
                                <strong class="fs-5 total"></strong> <!-- Add fs-5 class for bigger text -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 text-end">
                                Due:
                            </div>
                            <div class="col-sm-6 text-end due">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script></script>
