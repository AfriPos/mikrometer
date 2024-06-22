<?php

namespace App\Http\Controllers;

use App\Models\PoolModel;
use App\Models\RouterCredential;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\RouterosController;
use Illuminate\Http\Request;
use App\MyHelper\RouterosAPI;
use Illuminate\Support\Facades\DB;

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
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'ip_address' => 'required',
                'name' => 'required',
                'ranges' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            } else {
                // Create an instance of the RouterosAPI class
                $api = new RouterosAPI();

                // fetch router login credentials
                $routerCredential = RouterCredential::first();

                // Connect to the RouterOS
                if ($api->connect($request->ip_address, $routerCredential['login'], $routerCredential['password'])) {

                    DB::beginTransaction();

                    try {
                        // Check if the IP pool already exists by name or IP ranges
                        $existingPools = $api->comm('/ip/pool/print');
                        $poolExists = false;

                        foreach ($existingPools as $pool) {
                            if ($pool['name'] == $request->name || $pool['ranges'] == $request->ranges) {
                                $poolExists = true;
                                break;
                            }
                        }

                        if ($poolExists) {
                            $api->disconnect();
                            return redirect()->back()->with('error', "IP pool name: $request->name already exists");
                            DB::rollBack();
                        }

                        // Command to create an IP pool
                        $response = $api->comm('/ip/pool/add', [
                            'name' => $request->name,
                            'ranges' => $request->ranges
                        ]);

                        $ippool = new PoolModel();
                        $ippool->name = $request->name;
                        $ippool->router = $request->ip_address;
                        $ippool->ranges = $request->ranges;
                        $ippool->save();

                        DB::commit();

                        return redirect()->back()->with('success', "Successfully added IP pool: $request->name");
                        // Disconnect from the RouterOS
                        $api->disconnect();
                    } catch (\Throwable $e) {
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Error fetching data from RouterOS API!');
                    }
                } else {
                    return redirect()->back()->with('error', 'Failed to establish RouterOS API connection!');
                }
            }
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Error fetching data from RouterOS API!');
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
