<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Models\financerecordsModel;
use App\Models\InvoiceModel;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class invoiceController extends Controller
{
    public function store(Request $request, $customerId)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'created_at' => 'nullable|date',
                'due_date' => 'nullable|date',
                'amount' => 'required|numeric',
                'invoice_number' => 'required|string',
                'comment' => 'nullable|string',
            ]);

            $customer = CustomerModel::where('id', $customerId)->first();
            if ($customer->account_balance >= $validatedData['amount']) {
                $dueamount = $validatedData['amount'];
                $status = 'paid';
            } elseif ($customer->account_balance < $validatedData['amount'] && $customer->account_balance > 0) {
                $dueamount = -($validatedData['amount'] - $customer->account_balance);
                $status = 'partially paid';
            } else {
                $dueamount = -$validatedData['amount'];
                $status = 'unpaid';
            }
            $customer->account_balance -= $validatedData['amount'];
            $customer->save();
            
            // Create a new invoice record
            $invoice = InvoiceModel::create([
                'invoice_number' => $validatedData['invoice_number'],
                'customer_id' => $customerId,
                'amount' => $validatedData['amount'],
                'due_amount' => $dueamount,
                'due_date' => $validatedData['due_date'] ?? now()->addWeeks(2),
                'status' => $status,
                'type' => 'one time invoice',
                'created_at' => $validatedData['created_at'],
            ]);

            // Insert invoice record
            $invoiceRecord = new financerecordsModel();
            $invoiceRecord->type = 'invoice'; // Set the type
            $invoiceRecord->recordable_id = $invoice->id;
            $invoiceRecord->recordable_type = InvoiceModel::class;
            $invoiceRecord->amount = $validatedData['amount'];
            $invoiceRecord->comment = $validatedData['comment'];
            $invoiceRecord->customer_id = $customerId;
            $invoiceRecord->transaction_id = $validatedData['invoice_number'];
            $invoiceRecord->save();

            DB::commit();
            return redirect()->back()->with('success', 'Invoice created succesfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            return redirect()->back()->with('error', 'An error occured.');
        }
    }
}
