<?php

namespace App\Http\Controllers;

use App\Models\CustomerSubscriptionModel;
use App\Models\dataUsage;
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
        $username = $request->input('pppoe');

        if ($username) {
            $activeSessions = radacct::where('username', $username)
                ->where('acctstoptime', null)
                ->get();

            if ($activeSessions->isNotEmpty()) {
                return response()->json([
                    'success' => true,
                    'active_sessions' => $activeSessions,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No active sessions found for this username.',
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No username provided.',
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
        $dataUsage = dataUsage::where('username', $username)
            ->whereBetween('period_start', [$startDate, $endDate])
            ->where(function ($query) {
                $query->where('acctinputoctets', '>', 0)
                    ->orWhere('acctoutputoctets', '>', 0);
            })
            ->selectRaw('SUM(acctinputoctets) as total_download, SUM(acctoutputoctets) as total_upload')
            ->first();

        $radacctData = radacct::where('username', $username)
            ->whereBetween('acctstarttime', [$startDate, $endDate])
            ->whereNotNull('acctstoptime')
            ->selectRaw('SUM(acctsessiontime) as total_uptime, COUNT(*) as total_sessions')
            ->first();

        return [
            'total_download' => $dataUsage->total_download ?? 0,
            'total_upload' => $dataUsage->total_upload ?? 0,
            'total_uptime' => $radacctData->total_uptime ?? 0,
            'total_sessions' => $radacctData->total_sessions ?? 0,
        ];
    }

    public function showEndedSessions(Request $request, $username)
    {
        $startDate = Carbon::createFromDate($request->query('start_date'));
        $endDate = Carbon::createFromDate($request->query('end_date'));

        $query = radacct::where('username', $username)
            ->whereNotNull('acctstoptime')
            ->orderBy('acctstoptime', 'desc');

        if ($startDate && $endDate) {
            $query->whereBetween('acctstoptime', [$startDate->startOfDay(), $endDate->endOfDay()]);
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
