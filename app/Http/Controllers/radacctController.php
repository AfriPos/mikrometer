<?php

namespace App\Http\Controllers;

use App\Models\CustomerSubscriptionModel;
use App\Models\radacct;
use Carbon\Carbon;
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
    /**
     * Get download, upload, errors, and total uptime data for a specific username and date range.
     *
     * @param string $username
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getDataTotals($username, $startDate, $endDate)
    {
        $data = radacct::where('username', $username)
            ->whereBetween('acctstarttime', [$startDate, $endDate])
            ->selectRaw('SUM(acctinputoctets) as total_upload, SUM(acctoutputoctets) as total_download, SUM(acctsessiontime) as total_uptime, COUNT(*) as total_sessions')
            ->first();

        return [
            'total_download' => $data->total_download ?? 0,
            'total_upload' => $data->total_upload ?? 0,
            'total_uptime' => $data->total_uptime ?? 0,
            'total_sessions' => $data->total_sessions ?? 0,
        ];
    }
   
    public function showEndedSessions(Request $request, $username)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = radacct::where('username', $username)
            ->whereNotNull('acctstoptime')
            ->orderBy('acctstoptime', 'desc');

        if ($startDate && $endDate) {
            $query->whereBetween('acctstoptime', [$startDate, $endDate]);
        }

        $endedSessions = $query->get();

        if ($endedSessions->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'data' => $endedSessions,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No ended sessions found for this username within the specified date range.',
            ]);
        }
    }

}
