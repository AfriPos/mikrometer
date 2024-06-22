<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Models\CustomerSubscriptionModel;
use App\Models\IPAddressesModel;
use App\Models\PPPoEProfile;
use Illuminate\Http\Request;

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

            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',
                'phone' => 'required|string|max:20',
            ]);

            // Create a new customer record
            CustomerModel::create($validatedData);

            // Redirect back to the form with a success message
            return redirect()->back()->with('success', 'Customer added successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $th->getMessage()]);
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
        $pppoeprofiles = PPPoEProfile::all();
        $services = CustomerSubscriptionModel::where('customer_id', $customer->id)->get();
        $ipaddress = IPAddressesModel::where('customer_id', $customer->id)->first();
        return view('customer.edit', compact('customer', 'pppoeprofiles', 'services', 'ipaddress'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerModel $customer)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email,' . $customer->id,
                'phone' => 'required|string|max:20',
            ]);

            // Update the customer record
            $customer->update($validatedData);

            // Redirect back to the form with a success message
            return redirect()->back()->with('success', 'Customer Data has been updated successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $th->getMessage()]);
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
