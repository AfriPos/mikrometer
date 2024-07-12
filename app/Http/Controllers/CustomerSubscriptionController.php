<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
                'ipaddress' => 'required|string|max:255',
                'pppoe_login' => 'required|string|max:255',
                'pppoe_password' => 'required|string|max:255',
                'service_price' => 'required|integer',
            ]);

            $this->allocateIpAddress($request->ipaddress, $customerId);
            // $network = $request->input('serviceippool');
            // $networkip = $this->getNetworkIp($network);

            $subscription = new CustomerSubscriptionModel();
            $subscription->service = $request->service;
            $subscription->service_price = $request->service_price;
            $subscription->start_date = now();
            $subscription->pppoe_password = $request->pppoe_password;
            $subscription->pppoe_login = $request->pppoe_login;
            $subscription->status = 'active';
            $subscription->ipaddress = $request->ipaddress;
            $subscription->customer_id = $customerId;
            $subscription->save();

            $radreply = new radreply();
            $radreply->username = $request->pppoe_login;
            $radreply->attribute = 'Framed-IP-Address';
            $radreply->op = ':=';
            $radreply->value = $request->ipaddress;
            $radreply->customer_subscription_id = $subscription->id;
            $radreply->save();

            // $radreply1 = new radreply();
            // $radreply1->username = $request->pppoe_login;
            // $radreply1->attribute = 'Address-List';
            // $radreply1->op = ':=';
            // $radreply1->value = 'active';
            // $radreply1->customer_subscription_id = $subscription->id;
            // $radreply1->save();


            $radcheck = new radcheck();
            $radcheck->username = $request->pppoe_login;
            $radcheck->attribute = 'Cleartext-Password';
            $radcheck->op = ':=';
            $radcheck->value = $request->pppoe_password;
            $radcheck->customer_subscription_id = $subscription->id;
            $radcheck->save();

            $radcheck2 = new radcheck();
            $radcheck2->username = $request->pppoe_login;
            $radcheck2->attribute = 'User-Profile';
            $radcheck2->op = ':=';
            $radcheck2->value = $request->service;
            $radcheck2->customer_subscription_id = $subscription->id;
            $radcheck2->save();

            DB::commit();

            // return response()->json(['success' => 'Service created and activated successfully.']);
            return redirect()->back()->with('success', 'Service created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            // return redirect()->back()->with('error', 'An error occured while creating the service');
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
        // dd($request->all());

        // begin transaction
        DB::beginTransaction();

        try {
            // Validate form data
            $validatedData = $request->validate([
                'status' => 'required|string|max:255',
                'ipaddress' => 'required|string|ip|max:255',
                'pppoe_login' => 'required|string|max:255',
                'pppoe_password' => 'required|string|max:255',
                'service_price' => 'required|integer',
            ]);

            // Release previously assigned IP address if new IP is different
            if ($subscriptionid->ipaddress != $validatedData['ipaddress']) {
                $this->releaseIpAddress($subscriptionid->username);

                // Allocate new IP address
                $this->allocateIpAddress($validatedData['ipaddress'], $subscriptionid->pppoe_login);

                // Update radreply table
                $radreply = Radreply::where('username', $subscriptionid->pppoe_login)
                    ->where('attribute', 'Framed-IP-Address')
                    ->first();
                if ($radreply) {
                    $radreply->update([
                        'value' => $validatedData['ipaddress']
                    ]);
                } else {
                    Radreply::create([
                        'username' => $subscriptionid->pppoe_login,
                        'attribute' => 'Framed-IP-Address',
                        'op' => '=',
                        'value' => $validatedData['ipaddress']
                    ]);
                }
            }

            // Update the pppoe_password if new password is different
            if ($subscriptionid->pppoe_password != $validatedData['pppoe_password']) {
                $radcheck = Radcheck::where('username', $subscriptionid->pppoe_login)
                    ->where('attribute', 'Cleartext-Password')
                    ->first();
                if ($radcheck) {
                    $radcheck->update([
                        'value' => $validatedData['pppoe_password']
                    ]);
                } else {
                    Radcheck::create([
                        'username' => $subscriptionid->pppoe_login,
                        'attribute' => 'Cleartext-Password',
                        'op' => '=',
                        'value' => $validatedData['pppoe_password']
                    ]);
                }
            }


            // Update the pppoe_login if new login is different
            if ($subscriptionid->pppoe_login != $validatedData['pppoe_login']) {
                // Update Radcheck table
                $radcheckRecords = Radcheck::where('username', $subscriptionid->pppoe_login)->get();

                foreach ($radcheckRecords as $radcheck) {
                    $radcheck->update([
                        'username' => $validatedData['pppoe_login']
                    ]);
                }

                // Update Radreply table
                $radreplyRecords = Radreply::where('username', $subscriptionid->pppoe_login)->get();

                foreach ($radreplyRecords as $radreply) {
                    $radreply->update([
                        'username' => $validatedData['pppoe_login']
                    ]);
                }
            }

            // Update subscription details
            $subscriptionid->update($validatedData);

            DB::commit();
            // dd($subscriptionid);
            return redirect()->back()->with('success', 'Customer subscription updated successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            // return redirect()->back()->with('error', 'An error occured while creating the service');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerSubscriptionModel $subscriptionid)
    {
        $radcheck = radcheck::where('customer_subscription_id', $subscriptionid->id)->get();
        $radreply = radreply::where('customer_subscription_id', $subscriptionid->id)->get();
        // Release IP addresses assigned to the customer
        $this->releaseIpAddress($subscriptionid->customer->id);

        // Delete radcheck and radreply records
        $radcheck->each->delete();
        $radreply->each->delete();

        // Delete the customer subscription
        $subscriptionid->delete();

        return redirect()->back()->with('success', 'Customer subscription deleted successfully!');
    }

    // allocates an ip to a customer
    private function allocateIpAddress($ipaddress, $customerId)
    {
        DB::table('ip_addresses')
            ->where('ip_address', $ipaddress)
            ->update([
                'is_used' => true,
                'customer_id' => $customerId,
                'allocated_at' => now()
            ]);
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
