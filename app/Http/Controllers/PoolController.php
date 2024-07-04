<?php

namespace App\Http\Controllers;

use App\Models\IPAddressesModel;
use App\Models\PoolModel;
use App\Models\RouterCredential;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\RouterosController;
use App\Jobs\InsertIPPoolJob;
use Illuminate\Http\Request;
use App\MyHelper\RouterosAPI;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PoolController extends Controller
{


    public $API = [], $routeros_data = [], $connection;

    private $routerosController;

    public function __construct()
    {
        $this->routerosController = new RouterosController();
        $this->API = new RouterosAPI(); // This line should be $this->API = new RouterosAPI();
    }


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
        return view('ippool', compact('routers'));
    }

    /**
     * Store a newly created resource in storage.
     */ public function store(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'network' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            try {
                // Generate a unique 9-digit pool ID
                $poolId = mt_rand(100000000, 999999999);

                $ippool = new PoolModel();
                $ippool->id = $poolId; // Use the random 9-digit number for pool ID
                $ippool->name = $request->name;
                $ippool->network = $request->network;
                $ippool->save();

                // Limit the number of IP addresses to be added
                $limit = 65791; // Maximum number of IPs to add
                $ipGenerator = $this->listIpsInRangeLimited($request->network, $limit);

                $batchSize = 50;
                $ipBatch = [];
                $currentTime = now();
                foreach ($ipGenerator as $ip) {
                    $ipBatch[] = [
                        'ip_address' => $ip,
                        'pool_id' => $poolId,
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                    ];

                    if (count($ipBatch) >= $batchSize) {
                        IPAddressesModel::insert($ipBatch);
                        $ipBatch = [];
                    }
                }

                // Insert any remaining IP addresses
                if (!empty($ipBatch)) {
                    IPAddressesModel::insert($ipBatch);
                }

                DB::commit();

                return redirect()->back()->with('success', "Successfully added IP pool: {$request->name}");
            } catch (\Throwable $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Error creating IP pool: ' . $e->getMessage());
            }
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'System Error: ' . $e->getMessage());
        }
    }

    public function listIpsInRangeLimited($ipRange, $limit)
    {
        list($baseIp, $cidr) = explode('/', $ipRange);
        $ip = ip2long($baseIp);
        $numHosts = (1 << (32 - $cidr)) - 2; // Subtract 2 for network and broadcast addresses

        // Ensure we do not exceed the specified limit
        $numHosts = min($numHosts, $limit);

        $count = 0;
        for ($i = 1; $i <= $numHosts; $i++) {
            yield long2ip($ip + $i);
            $count++;

            // Check if we have reached the limit
            if ($count >= $limit) {
                break;
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PoolModel $poolModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PoolModel $poolModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PoolModel $poolModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PoolModel $poolModel)
    {
        //
    }
}
