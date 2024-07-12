{{-- <div class="table-responsive"> --}}
    <table class="table align-items-center mb-0 data-table1 w-100">
        <thead>
            <th>Type</th>
            <th>Number</th>
            <th>Date</th>
            <th>Total</th>
            <th>Due</th>
            <th>Payment Date</th>
            <th>Status</th>
            <th>Action</th>
        </thead>
        <tbody>
            @foreach ($invoices as $invoice)
                <tr>
                    <td>
                        <span
                            class="badge bg-{{ $invoice->type == 'recurring invoice' ? 'dark' : ($invoice->type == 'one time invoice' ? 'primary' : '') }}">
                            {{ $invoice->type }}
                        </span>
                    </td>
                    <td>#{{ $invoice->id }}</td>
                    <td>{{ $invoice->created_at }}</td>
                    <td>{{ $invoice->amount }}</td>
                    <td>{{ $invoice->due_date }}</td>
                    <td>{{ $invoice->created_at }}</td>
                    <td>
                        <span
                            class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : ($invoice->status == 'unpaid' ? 'info' : '')) }}">
                            {{ $invoice->status }}
                        </span>
                    </td>
                    <td>
                        <button class="p-1" data-bs-toggle="modal" data-bs-target="#viewinvoice{{ $invoice->id }}"><i
                                class="text-primary fa fa-eye"></i></button>
                        <button class="p-1" data-bs-toggle="modal" data-bs-target="#editinvoice{{ $invoice->id }}"><i
                                class="text-primary fa fa-edit"></i></button>
                        <button class="p-1"><i class="text-primary fa fa-file-pdf"></i></button>
                        <button class="p-1"><i class="text-primary fa fa-paper-plane"></i></button>
                        <button class="p-1"><i class="text-primary fa fa-trash"></i></button>
                    </td>
                </tr>
                <!-- invoices view modal include -->
                @include('finance.invoices.view')
                <!-- invoices view modal include -->
                @include('finance.invoices.edit')
            @endforeach
            @foreach ($payments as $payment)
                <tr>
                    <td><span class="badge bg-success">Payment</span></td>
                    <td>#{{ $payment->transaction_id }}</td>
                    <td>{{ $payment->created_at }}</td>
                    <td>{{ $payment->amount }}</td>
                    <td> </td>
                    <td>{{ $payment->created_at }}</td>
                    <td> </td>
                    <td>
                        <button class="p-1" data-bs-toggle="modal"
                            data-bs-target="#viewpayment{{ $payment->id }}"><i
                                class="text-primary fa fa-eye"></i></button>
                        <button class="p-1" data-bs-toggle="modal"
                            data-bs-target="#editpayment{{ $payment->id }}"><i
                                class="text-primary fa fa-edit"></i></button>
                        <button class="p-1"><i class="text-primary fa fa-file-pdf"></i></button>
                        <button class="p-1"><i class="text-primary fa fa-paper-plane"></i></button>
                        <button class="p-1"><i class="text-primary fa fa-trash"></i></button>
                    </td>
                </tr>
                <!-- payments view modal include -->
                @include('finance.payments.view')
                <!-- payments view modal include -->
                @include('finance.payments.edit')
            @endforeach
        </tbody>
    </table>
</div>



{{-- <!-- payment create modal include -->
@include('finance.payments.create') --}}
