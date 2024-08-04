<!-- Create service Modal -->
<div class="modal fade" id="editpayment{{ $record->recordable->id }}" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit payment</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <form method="POST" action="{{ route('payment.update', ['payment' => $record->recordable->id]) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="" disabled>Select Payment Type</option>
                            <option value="cash" {{ $record->recordable->payment_method == 'cash' ? 'selected' : '' }}>Cash
                            </option>
                            <option value="mpesa" {{ $record->recordable->payment_method == 'mpesa' ? 'selected' : '' }}>M-Pesa
                            </option>
                        </select>
                        <label for="payment_method" class="form-label">Payment Type</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="payment_date" name="payment_date" placeholder=""
                            value={{ $record->recordable->created_at }}>
                        <label for="payment_date" class="form-label">Date</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="amount" name="amount" placeholder=""
                            value={{ $record->recordable->amount }}>
                        <label for="amount" class="form-label">Amount</label>
                    </div>
                    
                    <div class="form-floating mb-3 d-flex">
                        <input type="text" class="form-control" id="edit_transaction_id" name="transaction_id"
                            placeholder="" value="{{ $record->recordable->transaction_id }}">
                        <label for="edit_transaction_id" class="form-label">Receipt Number</label>
                        <div class="input-group-append ms-2 form-floating">
                            <button type="button" class="form-control btn btn-outline-primary"
                                data-target="edit_transaction_id" onclick="setReceiptNumber(this)"><i
                                    class="fa-solid fa-wand-sparkles"></i></button>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="comment" name="comment" rows="3">{{ $record->recordable->comment }}</textarea>
                        <label for="comment" class="form-label">Comment for employees</label>
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


<script></script>
