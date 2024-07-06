<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Models\CustomerSubscriptionModel;
use App\Models\PPPoEService;
use App\Models\radcheck;
use App\Models\radreply;
use App\Models\RouterCredential;
use App\MyHelper\RouterosAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $customerId)
    {
        // begin transaction
        DB::beginTransaction();

        try {
            // Validate form data
            $request->validate([
                'service' => 'required|string|max:255',
                'serviceippool' => 'required|string|max:255',
                'pppoe_login' => 'required|string|max:255',
                'pppoe_password' => 'required|string|max:255',
                'service_price' => 'required|integer',
            ]);

            $allocatedIp = $this->allocateIpAddress($customerId);
            $network = $request->input('serviceippool');
            $networkip = $this->getNetworkIp($network);

            // // Create subscription
            // $subscription = new CustomerSubscriptionModel();
            // $subscription->pppoe_id = $request->input('service');
            // $subscription->pppoe_login = $request->input('pppoe_login');
            // $subscription->pppoe_password = $request->input('pppoe_password');
            // $subscription->service_price = $request->input('service_price');
            // $subscription->local_address = $networkip;
            // $subscription->remote_address = $allocatedIp;
            // $subscription->customer_id = $customerId;
            // $subscription->start_date = now();
            // $subscription->end_date = now()->addMonth(); // Example for a 1-month duration
            // $subscription->invoiced_till = now(); // Set this based on your logic
            // $subscription->status = 'active';
            // $subscription->save();

            $radreply = new radreply();
            $radreply->username = $request->pppoe_login;
            $radreply->attribute = 'Framed-IP-Address';
            $radreply->op = ':=';
            $radreply->value = $allocatedIp;
            $radreply->save();


            $radcheck = new radcheck();
            $radcheck->username = $request->pppoe_login;
            $radcheck->attribute = 'Cleartext-Password';
            $radcheck->op = ':=';
            $radcheck->value = $request->pppoe_password;
            $radcheck->save();

            $radcheck2 = new radcheck();
            $radcheck2->username = $request->pppoe_login;
            $radcheck2->attribute = 'User-Profile';
            $radcheck2->op = ':=';
            $radcheck2->value = $request->service;
            $radcheck2->save();
            DB::commit();

            // return response()->json(['success' => 'Service created and activated successfully.']);
            return redirect()->back()->with('success', 'Service created and activated successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            // return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        try {
            $subscription = CustomerSubscriptionModel::where('id', $request->subscriptionid)->first();

            // Return the JSON response
            return response()->json([
                'success' => true,
                'subscription' => $subscription,
            ]);
        } catch (\Throwable $th) {
            // Return the JSON response
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerSubscriptionModel $subscriptionid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerSubscriptionModel $subscriptionid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerSubscriptionModel $subscriptionid)
    {
        // Release IP addresses assigned to the customer
        $this->releaseIpAddress($subscriptionid->customer->id);

        // Delete the customer subscription
        $subscriptionid->delete();

        return redirect()->back()->with('success', 'Customer subscription deleted successfully!');
    }

    // allocates an ip to a customer
    private function allocateIpAddress($customerId)
    {
        $ipAddress = DB::table('ip_addresses')
            ->where('is_used', false)
            ->where('usable', true)
            ->first();


        if ($ipAddress) {
            DB::table('ip_addresses')
                ->where('id', $ipAddress->id)
                ->update([
                    'is_used' => true,
                    'customer_id' => $customerId,
                    'allocated_at' => now()
                ]);

            return $ipAddress->ip_address;
        }

        return null; // No available IP address
    }

    // releases all ips assinged to a customer
    private function releaseIpAddress($customerId)
    {
        DB::table('ip_addresses')
            ->where('customer_id', $customerId)
            ->update([
                'is_used' => false,
                'customer_id' => null,
                'allocated_at' => null
            ]);
    }

    // gets the network ip from a given subnet
    private function getNetworkIp($cidr)
    {
        list($ip, $prefixLength) = explode('/', $cidr);

        // Convert IP address to a long integer
        $ipLong = ip2long($ip);

        // Create the subnet mask
        $subnetMask = ~((1 << (32 - $prefixLength)) - 1);

        // Calculate the network address
        $network = $ipLong & $subnetMask;

        // Convert the network address back to an IP address
        $networkIp = long2ip($network);

        return $networkIp;
    }
}
