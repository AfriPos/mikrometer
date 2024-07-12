<!-- Create service Modal -->
<div class="modal fade" id="viewpayment{{ $payment->id }}" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">View payment</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-3">Customer:</div>
                    <div class="col-3">{{ $payment->customer->name }}</div>
                    <div class="col-3">Invoice number:</div>
                    <div class="col-3">{{ $payment->invoice_id }}</div>
                </div>
                <div class="row">
                    <div class="col-3">Payment type:</div>
                    <div class="col-3">{{ $payment->payment_method }}</div>
                    <div class="col-3">Sum:</div>
                    <div class="col-3">{{ $payment->amount }}</div>
                </div>
                <div class="row">
                    <div class="col-3">Transaction ID:</div>
                    <div class="col-3">2</div>
                    <div class="col-3">Transaction time:</div>
                    <div class="col-3">{{ $payment->created_at }}</div>
                    <div class="col-3">Receipt Number:</div>
                    <div class="col-3">{{ $payment->transaction_id }}</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>