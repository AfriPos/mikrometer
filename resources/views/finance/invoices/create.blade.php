<!-- Create payment Modal -->
<div class="modal fade" id="createinvoice" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Create Payment</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <form method="POST" action="{{ route('invoice.store', ['customer' => $customer->id]) }}">
                <div class="modal-body">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="payment_date" name="created_at" placeholder="">
                        <label for="payment_date" class="form-label">Invoice Date</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="dueDate" name="due_date" placeholder="">
                        <label for="dueDate" class="form-label">Due Date</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="amount" name="amount" placeholder="">
                        <label for="amount" class="form-label">Amount</label>
                    </div>

                    <div class="form-floating mb-3 d-flex">
                        <input type="text" class="form-control" id="invoiceNumber" name="invoice_number" placeholder="">
                        <label for="invoiceNumber" class="form-label">Invoice Number</label>
                        <div class="input-group-append ms-2 form-floating">
                            <button type="button" class="form-control btn btn-outline-primary"
                                data-target="invoiceNumber" onclick="setReceiptNumber(this)"><i
                                    class="fa-solid fa-wand-sparkles"></i></button>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="comment" name="comment" rows="3" placeholder=""></textarea>
                        <label for="comment" class="form-label">Note to customer</label>
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
