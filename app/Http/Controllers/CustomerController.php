<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Models\CustomerSubscriptionModel;
use App\Models\financerecordsModel;
use App\Models\InvoiceModel;
use App\Models\IPAddressesModel;
use App\Models\paymentModel;
use App\Models\PoolModel;
use App\Models\PPPoEProfile;
use App\Models\PPPoEService;
use App\Models\radreply;
use App\Models\radusergroup;
use App\Models\RouterCredential;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = CustomerModel::all();
        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();


            // Validate the incoming request data
            $validatedData = $request->validate([
                'portal_login' => 'nullable|string|max:191',
                'portal_password' => 'nullable|string|max:191',
                'name' => 'required|string|max:191',
                'email' => 'required|email|unique:customers,email,',
                'phone' => 'required|string|max:20',
                'service_type' => 'nullable|in:recurring,prepaid',
                'billing_email' => 'nullable|email',
                'street' => 'nullable|string|max:191',
                'zip_code' => 'nullable|string|max:20',
                'city' => 'nullable|string|max:191',
                'geo_data' => 'nullable|string|max:191',
                'category' => 'nullable|in:individual,business',
                'mpesa_phone' => 'nullable|string|max:20',
                'dob' => 'nullable|date',
                'id_number' => 'nullable|string|max:191',
            ]);

            // Create a new customer record
            $customer = CustomerModel::create($validatedData);

            radreply::created([
                'username' => $customer->portal_login,
                'attribute' => 'Mikrotik-Address-List',
                'op' => '=',
                'value' => 'MM-blocked-list'
            ]);

            // Update the portal_login with the newly created customer's ID
            $customer->portal_login = $customer->id;
            $customer->save();
            // Commit the transaction
            DB::commit();

            // Redirect to the edit route of the customer using the recently added id
            return redirect()->route('customer.edit', $customer->id)->with('success', 'Customer added successfully!');
        } catch (\Throwable $th) {
            // Rollback the transaction if an exception occurs
            DB::rollBack();

            // dd($th);
            return redirect()->back()->withErrors(['error' => 'An error occurred']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerModel $customer)
    {
        return view('customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerModel $customer)
    {
        $pppoeprofiles = PPPoEService::all();
        // $subscription = ;
        $ipaddress = IPAddressesModel::where('customer_id', $customer->id)->first(); // Fetch all pools
        // Fetch all pools
        $ippools = PoolModel::all();
        $subscriptions = CustomerSubscriptionModel::where('customer_id', $customer->id)->get();

        // Fetch records with related invoices
        $records = financerecordsModel::with('recordable')->where('customer_id', $customer->id)->orderBy('id', 'asc')->get();
        
        // Initialize an array to hold the pools and their IPs
        $poolsWithIps = [];

        // Iterate through each pool and fetch 15 IPs where customer_id is null
        foreach ($ippools as $pool) {
            $ipaddresses = IPAddressesModel::where('pool_id', $pool->id)
                ->whereNull('customer_id')
                ->take(15)
                ->get();

            // Add the pool and its IPs to the array
            $poolsWithIps[] = [
                'pool' => $pool,
                'ips' => $ipaddresses,
            ];
        }
        $routers = RouterCredential::all();

        return view('customer.edit', compact('customer', 'pppoeprofiles', 'subscriptions', 'ipaddress', 'ippools', 'poolsWithIps', 'routers', 'records'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerModel $customer)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'portal_login' => 'required|string|max:191',
                'portal_password' => 'nullable|string|max:191',
                'status' => 'required|in:new,active,blocked,inactive',
                'name' => 'required|string|max:191',
                'email' => 'required|email|unique:customers,email,' . $customer->id,
                'phone' => 'required|string|max:20',
                'service_type' => 'nullable|in:recurring,prepaid',
                'billing_email' => 'nullable|email',
                'street' => 'nullable|string|max:191',
                'zip_code' => 'nullable|string|max:20',
                'city' => 'nullable|string|max:191',
                'geo_data' => 'nullable|string|max:191',
                'category' => 'nullable|in:individual,business',
                'mpesa_phone' => 'nullable|string|max:20',
                'dob' => 'nullable|date',
                'id_number' => 'nullable|string|max:191',
            ]);

            $radreply = radreply::updateOrCreate(
                ['username' => $request->portal_login, 'attribute' => 'Mikrotik-Address-List'],
                ['value' => $request->status === 'active' ? 'MM-allowed-list' : 'MM-blocked-list']
            );

            // Update the customer record
            $customer->update($validatedData);

            // if (in_array($status, ['blocked', 'inactive', 'active'])) {
            // Update the radcheck table with the new status

            // }

            // Redirect back to the form with a success message
            return redirect()->back()->with('success', 'Customer Data has been updated successfully!');
        } catch (\Throwable $th) {
            dd($th);
            // return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerModel $customer)
    {
        //
    }
}
