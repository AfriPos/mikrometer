<!-- Create payment Modal -->
<div class="modal fade" id="createpayment" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Create Payment</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <form method="POST" action="{{ route('payment.store', ['customer' => $customer->id]) }}">
                <div class="modal-body">
                    @csrf
                    <div class="form-floating mb-3">
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="" disabled selected>Select Payment Type</option>
                            <option value="cash">Cash</option>
                            <option value="mpesa">M-Pesa</option>
                        </select>
                        <label for="payment_method" class="form-label">Payment Type</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="payment_date" name="payment_date" placeholder="">
                        <label for="payment_date" class="form-label">Date</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="amount" name="amount" placeholder="">
                        <label for="amount" class="form-label">Amount</label>
                    </div>

                    <div class="form-floating mb-3 d-flex">
                        <input type="text" class="form-control" id="transaction_id" name="transaction_id"
                            placeholder="">
                        <label for="transaction_id" class="form-label">Receipt Number</label>
                        <div class="input-group-append ms-2 form-floating">
                            <button type="button" class="form-control btn btn-outline-primary"
                                data-target="transaction_id" onclick="setReceiptNumber(this)"><i
                                    class="fa-solid fa-wand-sparkles"></i></button>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="comment" name="comment" rows="3" placeholder=""></textarea>
                        <label for="comment" class="form-label">Comment for employees</label>
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
    function generateID() {
        var currentDate = new Date();
        var year = currentDate.getFullYear();
        var month = String(currentDate.getMonth() + 1).padStart(2, '0');
        var day = String(currentDate.getDate()).padStart(2, '0');
        var randomNumber = Math.floor(Math.random() * 100000).toString().padStart(5, '0');
        var receiptNumber = year + '-' + month + '-' + day + randomNumber;
        return receiptNumber;
    }

    function setReceiptNumber(button) {
        var targetId = button.getAttribute('data-target');
        var receiptNumber = generateID();
        document.getElementById(targetId).value = receiptNumber;
    }
</script>
