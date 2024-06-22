<?php

namespace App\Http\Controllers;

use App\Models\PoolModel;
use App\Models\PPPoEProfile;
use App\Models\PPPoeService;
use Illuminate\Http\Request;
use App\MyHelper\RouterosAPI;
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

            // Create an instance of the RouterosAPI class
            $api = new RouterosAPI();

            // Validate the form data
            $validated = $request->validate([
                'ip_address' => 'required|ip',
                'interface' => 'required|string',
                'service_name' => 'required|string',
                'rate_download' => 'required|numeric',
                'rate_download_unit' => 'required|string|in:kbps,mbps,gbps',
                'rate_upload' => 'required|numeric',
                'rate_upload_unit' => 'required|string|in:kbps,mbps,gbps',
                // 'burst_limit' => 'required|string',
                // 'burst_threshold' => 'required|string',
                // 'burst_time' => 'required|integer',
                // 'priority' => 'required|integer',
                // 'limit_at' => 'required|string',
            ]);

            // Convert rates to kbps
            $rate_download = $this->apendParameter($validated['rate_download'], $validated['rate_download_unit']);
            $rate_upload = $this->apendParameter($validated['rate_upload'], $validated['rate_upload_unit']);

            // fetch router login credentials
            $routerCredential = RouterCredential::first();

            // Connect to the RouterOS
            if ($api->connect($request->ip_address, $routerCredential['login'], $routerCredential['password'])) {
                // try {
                    // Create the PPP profile with additional parameters
                    $pppoe_profile = $api->comm('/ppp/profile/add', [
                        'name' => $validated['service_name'],
                        'rate-limit' => "$rate_download/$rate_upload",
                        // 'burst-limit' => $validated['burst_limit'],
                        // 'burst-threshold' => $validated['burst_threshold'],
                        // 'burst-time' => $validated['burst_time'],
                        // 'priority' => $validated['priority'],
                        // 'limit-at' => $validated['limit_at'],
                    ]);
                    // Create the PPPoE server interface
                    $pppoe_server = $api->comm('/interface/pppoe-server/server/add', [
                        'service-name' => $validated['service_name'],
                        'interface' => $validated['interface'],
                        'max-mtu' => '1480',
                        'max-mru' => '1480',
                        'authentication' => 'pap,chap,mschap1,mschap2',
                        'default-profile' => $validated['service_name'],
                        'disabled' => 'no',
                    ]);

                    // Disconnect from the router
                    $api->disconnect();

                    // save the data to a database
                    $pppoeprofile = new PPPoEProfile();
                    $pppoeprofile->profile_name = $request->service_name;
                    $pppoeprofile->rate_limit = "$rate_download/$rate_upload";
                    $pppoeprofile->save();

                    $pppoe = new PPPoeService();
                    $pppoe->interface = $request->interface;
                    $pppoe->service_name = $request->service_name;
                    $pppoe->max_mtu = 1480;
                    $pppoe->max_mru = 1480;
                    $pppoe->profile_id = $pppoeprofile->id;
                    $pppoe->disabled = $request->status;
                    $pppoe->save();

                //     return redirect()->back()->with('success', 'PPPoE service created successfully!');
                // } catch (\Throwable $e) {
                //     DB::rollBack();
                //     return redirect()->back()->with('error', 'Failed to create PPPoE service!');
                // }
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Failed to connect to the router!');
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error fetching data from RouterOS API!', $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PPPoeService $pPPoeService)
    {
        //
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

