<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Models\CustomerSubscriptionModel;
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
        try {
            // Fetch customer details
            $customer = CustomerModel::findOrFail($customerId);

            // Validate form data
            $request->validate([
                'service' => 'required|string|max:255',
                'pppoe_login' => 'required|string|max:255',
                'pppoe_password' => 'required|string|max:255',
            ]);

            $this->releaseIpAddress($customerId);
            $allocatedIp = $this->allocateIpAddress($customerId);

            // Create subscription
            $subscription = new CustomerSubscriptionModel();
            $subscription->profile_id = $request->input('service');
            $subscription->pppoe_login = $request->input('pppoe_login');
            $subscription->pppoe_password = $request->input('pppoe_password');
            $subscription->local_address = $allocatedIp['local_address'];
            $subscription->remote_address = $allocatedIp['ip_address'];
            $subscription->customer_id = $customerId;
            $subscription->start_date = now();
            $subscription->end_date = now()->addMonth(); // Example for a 1-month duration
            $subscription->invoiced_till = now(); // Set this based on your logic
            $subscription->status = 'active';
            $subscription->save();


            return response()->json(['success' => 'Service created and activated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerSubscriptionModel $customerSubscriptionModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerSubscriptionModel $customerSubscriptionModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerSubscriptionModel $customerSubscriptionModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerSubscriptionModel $customerSubscriptionModel)
    {
        //
    }


    private function allocateIpAddress($customerId)
    {
        $ipAddress = DB::table('ip_addresses')
            ->where('is_used', false)
            ->where('usable', true)
            ->first();

        $localipAddress = DB::table('ip_addresses')
            ->where('is_used', false)
            ->orderBy('id')
            ->first();

        if ($ipAddress) {
            DB::table('ip_addresses')
                ->where('id', $ipAddress->id)
                ->update([
                    'is_used' => true,
                    'customer_id' => $customerId,
                    'allocated_at' => now()
                ]);

            $iparray = array(
                'ip_address' => $ipAddress->ip_address,
                'local_address' => $localipAddress->ip_address,
            );

            return $iparray;
        }

        return null; // No available IP address
    }

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
}
