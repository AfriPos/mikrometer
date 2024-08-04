<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Jobs\CheckExpiredSubscriptionsJob;
use App\Models\CustomerModel;
use Illuminate\Http\Request;
use App\Models\CustomerSubscriptionModel;
use App\Models\financerecordsModel;
use App\Models\InvoiceModel;
use App\Models\paymentModel;
use App\Models\PPPoEService;
use App\Models\radcheck;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

    public function generatePDF($id)
    {
        // Fetch a single record with related invoice using recordable_id
        $payment = financerecordsModel::with('recordable')->where('recordable_id', $id)->first();
        return view('finance.payments.pdf', compact('payment'));
    }


    public function index()
    {
        $payments = paymentModel::all();
        return view('finance.payments.index', compact('payments'));
    }

    public function create()
    {
        $customers = CustomerModel::all();
        return view('payment.create', compact('customers'));
    }

    public function store(Request $request, $customerId)
    {
        try {
            DB::beginTransaction();

            // Validate the request
            $validatedData = $request->validate([
                'amount' => 'required|numeric|min:1',
                'comment' => 'nullable|string',
                'payment_date' => 'nullable|date',
                'transaction_id' => 'required|string',
                'payment_method' => 'required|string',
            ]);

            $createdAt = $validatedData['payment_date'] ?? null;

            // Create a new payment record
            $payment = paymentModel::create($validatedData + ['created_at' => $createdAt, 'customer_id' => $customerId]);

            // Insert payment record
            $paymentRecord = new financerecordsModel();
            $paymentRecord->type = 'payment'; // Set the type
            $paymentRecord->recordable_id = $payment->id;
            $paymentRecord->recordable_type = paymentModel::class;
            $paymentRecord->amount = $validatedData['amount'];
            $paymentRecord->payment_method = $validatedData['payment_method'];
            $paymentRecord->transaction_id = $validatedData['transaction_id'];
            $paymentRecord->comment = $validatedData['comment'];
            $paymentRecord->customer_id = $customerId;

            // Find the customer subscription
            $subscription = CustomerSubscriptionModel::where('customer_id', $customerId)->first();
            $customer = CustomerModel::where('id', $customerId)->first();
            $unpaidInvoices = InvoiceModel::where('customer_id', $customer->id)
                ->whereIn('status', ['unpaid', 'partially paid', 'overdue'])
                ->count();

            $Amount = $validatedData['amount'];

            // Partially pay invoices
            $unpaidInvoices = InvoiceModel::where('customer_id', $customer->id)
                ->whereIn('status', ['unpaid', 'partially paid', 'overdue'])
                ->orderBy('created_at', 'asc')
                ->get();

            // loop through the invoices distributing the payment
            foreach ($unpaidInvoices as $invoice) {
                if ($Amount >= abs($invoice->due_amount)) {
                    $invoice->status = 'paid';
                    $invoice->due_amount = 0;
                    $invoice->save();
                    $Amount -= $invoice->amount;
                } elseif ($Amount > 0) {
                    $invoice->status = 'partially paid';
                    $invoice->due_amount = $Amount - $invoice->amount;
                    $invoice->save();
                    $Amount = 0;
                } else {
                    break;
                }
            }

            // update the customer's balance
            $customer->account_balance += $validatedData['amount'];
            $customer->save();
            // dd($subscription->invoiced_till && $subscription->invoiced_till < now() && $customer->status === 'blocked');
            // Update the customer status if the amount is correct
            if ($customer && $subscription && $subscription->invoiced_till < now() && $customer->status === 'blocked') {
                if ($customer->account_balance >= $subscription->service_price) {
                    $customer->status = 'active';
                    $customer->save();


                    $recordablegroup_id = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4);

                    $radcheck = radcheck::updateOrCreate(
                        [
                            'username' => $subscription->pppoe_login,
                            'attribute' => 'User-Profile',
                        ],
                        [
                            'op' => ':=',
                            'value' => $subscription->service,
                            'customer_subscription_id' => $subscription->id,
                        ]
                    );
                    // Calculate the invoiced_till date
                    $service = PPPoEService::where('service_name', $subscription->service)->first();
                    $duration = $service->service_duration;
                    $unit = $service->duration_unit;

                    // Calculate the invoiced_till date
                    $invoicedTill = Carbon::now()->add($duration, $unit);
                    $subscription->invoiced_till = $invoicedTill;
                    $subscription->save();

                    // Calculate half of the duration in minutes
                    $halfDurationInMinutes = $this->calculateHalfDuration($duration, $unit);
                    // Calculate the due date
                    $dueDate = Carbon::now()->addMinutes($halfDurationInMinutes);
                    // Generate an invoice number with a timestamp
                    $invoiceNumber = now()->format('YmdHis') . '-' . $customerId;
                    // Create a new invoice record
                    $invoice = InvoiceModel::create([
                        'invoice_number' => $invoiceNumber,
                        'customer_id' => $customerId,
                        'due_date' => $dueDate,
                        'amount' => $validatedData['amount'],
                        'due_amount' => 0,
                        'status' => 'paid',
                        'type' => 'one time invoice',
                        'created_at' => $createdAt
                    ]);

                    // Insert invoice record
                    $invoiceRecord = new financerecordsModel();
                    $invoiceRecord->type = 'invoice'; // Set the type
                    $invoiceRecord->recordable_id = $invoice->id;
                    $invoiceRecord->recordable_type = InvoiceModel::class;
                    $invoiceRecord->amount = $validatedData['amount'];
                    $invoiceRecord->comment = $validatedData['comment'];
                    $invoiceRecord->customer_id = $customerId;
                    $invoiceRecord->transaction_id = $invoiceNumber;

                    $paymentRecord->recordablegroup_id = $recordablegroup_id;
                    $invoiceRecord->recordablegroup_id = $recordablegroup_id;

                    $invoiceRecord->save();

                    $customer->account_balance -= $subscription->service_price;
                    $customer->save();
                }
            }
            $paymentRecord->save();

            DB::commit();

            return redirect()->back()->with('success', 'Payment processed succesfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            // return redirect()->back()->with('error', 'An error occurred: ' . $th->getMessage());
        }
    }

    public function update(Request $request, paymentModel $payment)
    {
        try {
            DB::beginTransaction();

            // Validate the request
            $validatedData = $request->validate([
                'amount' => 'required|numeric|min:1',
                'comment' => 'nullable|string',
                'payment_date' => 'nullable|date',
                'transaction_id' => 'required|string',
                'payment_method' => 'required|string',
            ]);

            // Update the payment record
            $payment->update($validatedData);

            DB::commit();
            return redirect()->back()->with('success', 'Payment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update payment: ' . $e->getMessage());
        }
    }

    public function destroy(financerecordsModel $payment)
    {
        try {
            DB::beginTransaction();
            $customer = CustomerModel::where('id', $payment->customer_id)->first();
            $recordable = $payment->recordable;

            // update the customer's balance
            $customer->account_balance -= $payment->amount;
            $customer->save();
            dump($customer);

            // if ($recordable) {
            //     $recordable->delete();
            // }

            // $payment->delete();

            // return response()->json(['message' => 'Finance record and its recordable deleted successfully']);
            // DB::commit();
            // return redirect()->back()->with('success', 'Payment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update payment: ' . $e->getMessage());
        }
    }

    public function dispatch()
    {
        // Somewhere in your application, such as a controller method or a command
        $job = new CheckExpiredSubscriptionsJob();
        $job->handle();
    }
    // Function to calculate half of a duration
    private function calculateHalfDuration($duration, $unit)
    {
        switch ($unit) {
            case 'minutes':
                return $duration / 2;
            case 'weeks':
                return $duration / 2 * 7 * 24 * 60; // Convert weeks to minutes
            case 'months':
                return $duration / 2 * 30.4375 * 24 * 60; // Approximate months to minutes
            case 'years':
                return $duration / 2 * 365 * 24 * 60; // Convert years to minutes
            default:
                throw new \InvalidArgumentException("Unsupported unit: $unit");
        }
    }
}
