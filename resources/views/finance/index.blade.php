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
        {{-- {{$records}} --}}
        @foreach ($records as $record)
            <tr>
                <td>{{$record->recordable->id}}</td>
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
                    <button class="p-1" data-bs-toggle="modal" data-bs-target="#viewinvoice{{ $record->id }}"><i
                            class="text-primary fa fa-eye"></i></button>
                    <button class="p-1" data-bs-toggle="modal" data-bs-target="#editinvoice{{ $record->id }}"><i
                            class="text-primary fa fa-edit"></i></button>
                    <button class="p-1"><i class="text-primary fa fa-file-pdf"></i></button>
                    <button class="p-1"><i class="text-primary fa fa-paper-plane"></i></button>
                    <button class="p-1"><i class="text-primary fa fa-trash"></i></button>
                </td>
            </tr>
            {{-- @if ($record->type == 'invoice')
                <!-- invoices view modal include -->
                @include('finance.invoices.view')
                <!-- invoices view modal include -->
                @include('finance.invoices.edit')
            @elseif ($record->type == 'payment')
                <!-- payments view modal include -->
                @include('finance.payments.view')
                <!-- payments view modal include -->
                @include('finance.payments.edit')
            @endif --}}
        @endforeach
    </tbody>
</table>
</div>



{{-- <!-- payment create modal include -->
@include('finance.payments.create') --}}
