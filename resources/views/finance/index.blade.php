{{-- <div class="table-responsive"> --}}
<table class="table align-items-center mb-0 data-table1 w-100">
    <thead>
        <th>#</th>
        <th>Type</th>
        <th>Number</th>
        <th>Date</th>
        <th>Total</th>
        <th>Due Amount</th>
        <th>Due</th>
        <th>Payment Date</th>
        <th>Status</th>
        <th>Action</th>
    </thead>
    <tbody>
        {{-- {{ $records }} --}}
        @foreach ($records as $record)
            <tr>
                <td>{{ $record->id }}</td>
                <td>
                    <span
                        class="badge bg-{{ $record->type == 'recurring invoice' ? 'dark' : ($record->type == 'invoice' ? 'primary' : ($record->type == 'payment' ? 'success' : '')) }}">
                        {{ $record->type }}
                    </span>
                </td>
                <td>#{{ $record->transaction_id }}</td>
                <td>{{ date('Y-m-d', strtotime($record->created_at)) }}</td>
                <td>{{ $record->amount }}</td>
                <td>{{ $record->recordable->due_amount }}</td>
                <td>{{ $record->recordable->due_date }}</td>
                <td>{{ date('Y-m-d', strtotime($record->created_at)) }}</td>
                <td>
                    <span
                        class="badge bg-{{ $record->recordable->status == 'paid' ? 'success' : ($record->recordable->status == 'overdue' ? 'danger' : ($record->recordable->status == 'unpaid' ? 'info' : ($record->recordable->status == 'partially paid' ? 'warning' : ''))) }}">
                        {{ $record->recordable->status }}
                    </span>
                </td>
                <td>
                    @if ($record->type == 'invoice')
                        <button class="btn btn-link p-0 me-1" data-bs-toggle="modal"
                            data-bs-target="#viewinvoice{{ $record->recordable->id }}">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button class="btn btn-link p-0 me-1" data-bs-toggle="modal"
                            data-bs-target="#editinvoice{{ $record->recordable->id }}">
                            <i class="fa fa-edit"></i>
                        </button>
                        <a href="{{ route('invoice.pdf', $record->recordable) }}" class="btn btn-link p-0 me-1"
                            target="_blank">
                            <i class="fa fa-file-pdf"></i>
                        </a>
                        <button class="btn btn-link p-0 me-1" data-bs-toggle="modal"
                            data-bs-target="#invoicecomms{{ $record->recordable->id }}">
                            <i class="fa fa-paper-plane"></i>
                        </button>
                        <a href="{{ route('invoice.destroy', $record->recordable->id) }}"
                            class="btn btn-link p-0 text-danger"
                            onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this invoice?')) { document.getElementById('delete-form-{{ $record->recordable->id }}').submit(); }">
                            <i class="fa fa-trash"></i>
                        </a>
                        <form id="delete-form-{{ $record->recordable->id }}"
                            action="{{ route('invoice.destroy', $record) }}" method="POST"
                            style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>

                        <!-- invoices view modal include -->
                        @include('finance.invoices.view')
                        <!-- invoices view modal include -->
                        @include('finance.invoices.edit')
                        <!-- invoices comunication modal include -->
                        @include('finance.invoices.communication')
                    @elseif ($record->type == 'payment')
                        <button class="btn btn-link p-0 me-1" data-bs-toggle="modal"
                            data-bs-target="#viewpayment{{ $record->recordable->id }}">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button class="btn btn-link p-0 me-1" data-bs-toggle="modal"
                            data-bs-target="#editpayment{{ $record->recordable->id }}">
                            <i class="fa fa-edit"></i>
                        </button>
                        <a href="{{ route('payment.pdf', $record->recordable) }}" class="btn btn-link p-0 me-1"
                            target="_blank">
                            <i class="fa fa-file-pdf"></i>
                        </a> <button class="btn btn-link p-0 me-1" data-bs-toggle="modal"
                            data-bs-target="#paymentcomms{{ $record->recordable->id }}">
                            <i class="fa fa-paper-plane"></i>
                        </button>
                        <a href="{{ route('payment.destroy', $record->recordable->id) }}"
                            class="btn btn-link p-0 text-danger"
                            onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this payment?')) { document.getElementById('delete-form-{{ $record->recordable->id }}').submit(); }">
                            <i class="fa fa-trash"></i>
                        </a>
                        <form id="delete-form-{{ $record->recordable->id }}"
                            action="{{ route('payment.destroy', $record->recordable->id) }}" method="POST"
                            style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <!-- payments view modal include -->
                        @include('finance.payments.view')
                        <!-- payments view modal include -->
                        @include('finance.payments.edit')
                        <!-- payments communication modal include -->
                        @include('finance.payments.communication')
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>



{{-- <!-- payment create modal include -->
@include('finance.payments.create') --}}
