<?php

namespace App\Http\Controllers;

use App\Models\IPAddressesModel;
use App\Jobs\ProcessIPAddresses;
use App\Models\PoolModel;
use App\Models\RouterCredential;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\RouterosController;
use App\MyHelper\RouterosAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PoolController extends Controller
{
    public $API = [], $routeros_data = [], $connection;
    private $routerosController;

    public function __construct()
    {
        $this->routerosController = new RouterosController();
        $this->API = new RouterosAPI();
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
                'name' => 'required',
                'network' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Generate a unique 9-digit pool ID
            $poolId = mt_rand(100000000, 999999999);

            $ippool = new PoolModel();
            $ippool->id = $poolId; // Use the random 9-digit number for pool ID
            $ippool->name = $request->name;
            $ippool->network = $request->network;
            $ippool->save();

            // Dispatch job to process IP addresses in the background
            ProcessIPAddresses::dispatch($request->network, $poolId);

            return redirect()->back()->with('success', "Successfully added IP pool: {$request->name}");
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Error creating IP pool: ' . $e->getMessage());
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
