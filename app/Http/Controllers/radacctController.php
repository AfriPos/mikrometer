<?php

namespace App\Http\Controllers;

use App\Models\CustomerSubscriptionModel;
use App\Models\radacct;
use Illuminate\Http\Request;

class radacctController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $radaccts = Radacct::paginate(15);
        return view('radacct.index', compact('radaccts'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $customerId = $request->input('customer_id');
        $subscriptions = CustomerSubscriptionModel::where('customer_id', $customerId)->get();
        $activeSessions = collect();

        foreach ($subscriptions as $subscription) {
            $sessions = radacct::where('username', $subscription->pppoe_login)
                               ->where('acctstoptime', null)
                               ->get();
            $activeSessions = $activeSessions->concat($sessions);
        }

        if ($activeSessions->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'active_sessions' => $activeSessions,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No active sessions found for this customer.',
            ]);
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(radacct $radacct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, radacct $radacct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(radacct $radacct)
    {
        //
    }
}
