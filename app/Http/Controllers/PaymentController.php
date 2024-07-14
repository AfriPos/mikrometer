<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Jobs\CheckExpiredSubscriptionsJob;
use App\Models\CustomerModel;
use Illuminate\Http\Request;
use App\Models\CustomerSubscriptionModel;
use App\Models\InvoiceModel;
use App\Models\paymentModel;
use App\Models\PPPoEService;
use App\Models\radreply;
use App\Models\RouterCredential;
use App\MyHelper\RouterosAPI;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

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
            // Find the customer subscription
            $subscription = CustomerSubscriptionModel::where('customer_id', $customerId)->first();
            $customer = CustomerModel::where('id', $customerId)->first();

            // Update the customer status if the amount is correct
            if ($customer && $subscription) {
                if ((int)$validatedData['amount'] >= $subscription->service_price) {
                    $customer->status = 'active';
                    $customer->save();

                    // $radreply1 = new radreply();
                    // $radreply1->username = $request->pppoe_login;
                    // $radreply1->attribute = 'Address-List';
                    // $radreply1->op = ':=';
                    // $radreply1->value = 'active';
                    // $radreply1->customer_subscription_id = $subscription->id;
                    // $radreply1->save();

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

                    // Create a new invoice record
                    $invoice = InvoiceModel::create($validatedData + ['type' => 'one time invoice', 'due_date' => $dueDate, 'status' => 'paid', 'created_at' => $createdAt, 'customer_id' => $customerId]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Payment processed succesfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            // return response()->json(['error' => $th], 500);
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




// // Find the customer subscription
// $subscription = CustomerSubscriptionModel::where('pppoe_login', $customer->id)->first();

// if ($subscription) {
//     // Update the end date and status
//     $subscription->end_date = now()->addMonth(); // Example: extend for 1 month from now
//     $subscription->status = 'active';
//     $subscription->save();
// }

// // return response()->json([
// //     'success' => true,
// //     'message' => $subscription
// // ]);

// // Create an instance of the RouterosAPI class
// $api = new RouterosAPI();
// // fetch router login credentials
// $routerCredential = RouterCredential::first();
// // Connect to the RouterOS
// if ($api->connect($routerCredential['ip_address'], $routerCredential['login'], $routerCredential['password'])) {
//     // Check if the secret already exists
//     $existingSecret = $api->comm("/ppp/secret/print", [
//         ".proplist" => ".id",
//         "?name" => $subscription->pppoe_login,
//     ]);

//     if (!empty($existingSecret)) {
//         // Enable existing secret
//         $api->comm("/ppp/secret/enable", [
//             ".id" => $existingSecret[0]['.id']
//         ]);
//     } else {
//         // Add new secret
//         $api->comm("/ppp/secret/add", [
//             "name" => $subscription->pppoe_login,
//             "password" => $subscription->pppoe_password,
//             "profile" => $subscription->profile_name,
//             "service" => "pppoe", // Set service to pppoe
//             "comment" => $customer->email . " | " . $customer->phone, // Add customer email and phone as comment
//         ]);
//     }

//     $api->disconnect();
// } else {
//     // Handle connection failure
//     return response()->json(['error' => 'Failed to connect to the router.'], 400);
// }