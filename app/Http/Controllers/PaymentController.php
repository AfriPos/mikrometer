<?php

namespace App\Http\Controllers;

use App\Jobs\CheckExpiredSubscriptionsJob;
use App\Models\CustomerModel;
use Illuminate\Http\Request;
use App\Models\CustomerSubscriptionModel;
use App\Models\paymentModel;
use App\Models\RouterCredential;
use App\MyHelper\RouterosAPI;

class PaymentController extends Controller
{

    public function index(){
        $payments = paymentModel::all();
        return view('payment.index', compact('payments'));
    }

    public function create()
    {
        $customers = CustomerModel::all();
        return view('payment.create', compact('customers'));
    }

    public function store(Request $request)
    {
        try {

            // Validate the request
            $request->validate([
                'customer' => 'required|exists:customers,id',
                'amount' => 'required|numeric',
            ]);

            $customer = CustomerModel::where('id', $request->input('customer'))->first();

            // Find the customer subscription
            $subscription = CustomerSubscriptionModel::where('pppoe_login', $customer->id)->first();

            if ($subscription) {
                // Update the end date and status
                $subscription->end_date = now()->addMonth(); // Example: extend for 1 month from now
                $subscription->status = 'active';
                $subscription->save();
            }

            // return response()->json([
            //     'success' => true,
            //     'message' => $subscription
            // ]);

            // Create an instance of the RouterosAPI class
            $api = new RouterosAPI();
            // fetch router login credentials
            $routerCredential = RouterCredential::first();
            // Connect to the RouterOS
            if ($api->connect($routerCredential['ip_address'], $routerCredential['login'], $routerCredential['password'])) {
                // Check if the secret already exists
                $existingSecret = $api->comm("/ppp/secret/print", [
                    ".proplist" => ".id",
                    "?name" => $subscription->pppoe_login,
                ]);

                if (!empty($existingSecret)) {
                    // Enable existing secret
                    $api->comm("/ppp/secret/enable", [
                        ".id" => $existingSecret[0]['.id']
                    ]);
                } else {
                    // Add new secret
                    $api->comm("/ppp/secret/add", [
                        "name" => $subscription->pppoe_login,
                        "password" => $subscription->pppoe_password,
                        "profile" => $subscription->profile_name,
                        "service" => "pppoe", // Set service to pppoe
                        "comment" => $customer->email . " | " . $customer->phone, // Add customer email and phone as comment
                    ]);
                }

                $api->disconnect();
            } else {
                // Handle connection failure
                return response()->json(['error' => 'Failed to connect to the router.'], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment processed and service activated.'
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function dispatch()
    {
        // Somewhere in your application, such as a controller method or a command
        $job = new CheckExpiredSubscriptionsJob();
        $job->handle();
    }
}
