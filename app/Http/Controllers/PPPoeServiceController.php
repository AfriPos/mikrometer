<?php

namespace App\Http\Controllers;

use App\Models\PoolModel;
use App\Models\PPPoeService;
use App\Models\radgroupcheck;
use App\Models\radgroupreply;
use App\Models\radusergroup;
use Illuminate\Http\Request;
use App\Models\RouterCredential;
use Illuminate\Support\Facades\DB;

class PPPoeServiceController extends Controller
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
        $routers = RouterCredential::all();
        $pools = PoolModel::all();
        return view('pppoe.create', compact('routers', 'pools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            DB::beginTransaction();


            // Validate the form data
            $validated = $request->validate([
                'service_name' => 'required|string',
                'service_price' => 'required|integer',
                'servie_duration' => 'required|numeric',
                'servie_duration_unit' => 'required|string|in:minutes,hours,days,weeks,months,years',
                'rate_download' => 'required|numeric',
                'rate_download_unit' => 'required|string|in:k,M,G',
                'rate_upload' => 'required|numeric',
                'rate_upload_unit' => 'required|string|in:k,M,G',
                'burst_rate_download' => 'nullable|numeric',
                'burst_rate_download_unit' => 'nullable|string|in:k,M,G',
                'burst_rate_upload' => 'nullable|numeric',
                'burst_rate_upload_unit' => 'nullable|string|in:k,M,G',
                'threshold_download' => 'nullable|numeric',
                'threshold_download_unit' => 'nullable|string|in:k,M,G',
                'threshold_upload' => 'nullable|numeric',
                'threshold_upload_unit' => 'nullable|string|in:k,M,G',
                'burst_time' => 'nullable|numeric',
                'priority' => 'nullable|numeric',
            ]);

            $limits = $request->rate_download . $request->rate_download_unit . '/' . $request->rate_upload . $request->rate_upload_unit . ' ' . $request->burst_rate_download . $request->burst_rate_download_unit . '/' . $request->burst_rate_upload . $request->burst_rate_upload_unit . ' ' . $request->threshold_download . $request->threshold_download_unit . '/' . $request->threshold_upload . $request->threshold_upload_unit . ' ' . $request->burst_time . '/' . $request->burst_time;


            // Convert rates to kbps
            $rate_download = $this->apendParameter($validated['rate_download'], $validated['rate_download_unit']);
            $rate_upload = $this->apendParameter($validated['rate_upload'], $validated['rate_upload_unit']);


                    // save the data to a database
                    $radgroupcheck = new radgroupcheck();
                    $radgroupcheck->groupname = $request->service_name;
                    $radgroupcheck->attribute = "Framed-Protocol";
                    $radgroupcheck->op = "==";
                    $radgroupcheck->value = "PPP";
                    $radgroupcheck->save();

                    // save the data to a database
                    $radgroupreply = new radgroupreply();
                    $radgroupreply->groupname = $request->service_name;
                    $radgroupreply->attribute = "Mikrotik-Rate-Limit";
                    $radgroupreply->op = "=";
                    $radgroupreply->value = $limits;
                    $radgroupreply->save();

                    // save the data to a database
                    $radusergroup = new radusergroup();
                    $radusergroup->username = $request->service_name;
                    $radusergroup->groupname = $request->service_name;
                    $radusergroup->priority = $request->priority;
                    $radusergroup->save();

                    $pppoe = new PPPoeService();
                    $pppoe->service_name = $request->service_name;
                    $pppoe->service_price = $request->service_price;
                    $pppoe->service_duration = $request->servie_duration;
                    $pppoe->duration_unit = $request->servie_duration_unit;
                    $pppoe->save();

                    DB::commit();

                    return redirect()->back()->with('success', 'PPPoE service created successfully!');
              
        } catch (\Throwable $e) {
            DB::rollBack();
            //return response()->json(['error' => 'Error fetching data from RouterOS API!', $e->getMessage()], 500);
            // return redirect()->back()->with('error', 'Failed to create PPPoE service!');
            return response()->json(['error' => 'Failed to create PPPoE service!', $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $service = PPPoeService::where('service_name', $request->serviceId)->first();

        if ($service) {
            // Return the JSON response
            return response()->json([
                'service' => $service,
            ]);
        } else {
            // Return the JSON response
            return response()->json([
                'success' => false,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PPPoeService $pPPoeService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PPPoeService $pPPoeService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PPPoeService $pPPoeService)
    {
        //
    }

    /**
     * Convert rate to kbps
     *
     * @param float $rate
     * @param string $unit
     * @return int
     */
    private function apendParameter($rate, $unit)
    {
        switch ($unit) {
            case 'mbps':
                return $rate . 'M'; // Append 'M' for Mbps
            case 'gbps':
                return $rate . 'G'; // Append 'G' for Gbps
            case 'kbps':
            default:
                return $rate . 'k'; // Append 'k' for kbps
        }
    }
}
