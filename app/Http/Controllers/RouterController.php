<?php

namespace App\Http\Controllers;

use App\Models\RouterCredential;
use Illuminate\Http\Request;
use App\MyHelper\RouterosAPI;
use App\Http\Controllers\api\RouterosController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RouterController extends Controller
{



    public $API = [], $routeros_data = [], $connection;


    private $routerosController;

    public function __construct()
    {
        $this->routerosController = new RouterosController();
    }

    public function fetchInterfaces(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
        ]);

        // Create an instance of the RouterosAPI class
        $api = new RouterosAPI();

        // Fetch router login credentials
        $routerCredential = RouterCredential::where('ip_address', $request->ip_address)->first();
        try {
            // Attempt to connect to the RouterOS
            if ($api->connect($request->ip_address, $routerCredential->login, $routerCredential->password)) {
                // Fetch interfaces
                $api->write('/interface/print');
                $interfaces = $api->read();

                // Disconnect from the router
                $api->disconnect();

                // Return the interfaces as JSON response
                return response()->json([
                    'success' => true,
                    'interfaces' => $interfaces,
                ]);
            }
            //  else {
            // }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to the router.' . $th->getMessage(),
            ]);
        }

    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routers = RouterCredential::all();
        return view('router.index', compact('routers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('router.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'ip_address' => 'required',
                'login' => 'required',
                'password' => 'required'
            ]);
            if ($validator->fails())
                return response()->json($validator->errors(), 404);
            $req_data = [
                'ip_address' => $request->ip_address,
                'login' => $request->login,
                'password' => $request->password,
            ];

            $routeros_db = RouterCredential::where('ip_address', $req_data['ip_address'])->get();

            if (count($routeros_db) > 0) {
                if (!$this->routerosController->check_routeros_connection($request->all())) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'RouterOS connection failed! check the administrator credentials!');
                }
            } else {

                $API = new RouterosAPI;
                $connection = $API->connect($request['ip_address'], $request['login'], $request['password']);
                if (!$connection) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Error connecting to the router!');
                }
                // var_dump($API->comm('/system/identity/print'));
                $store_routeros_data = [
                    'identity' => $API->comm('/system/identity/print')[0]['name'],
                    'ip_address' => $request['ip_address'],
                    'login' => $request['login'],
                    'password' => $request['password'],
                    'connect' => $connection

                ];
                $store_routeros = new RouterCredential;
                $store_routeros->identity = $store_routeros_data['identity'];
                $store_routeros->ip_address = $store_routeros_data['ip_address'];
                $store_routeros->login = $store_routeros_data['login'];
                $store_routeros->password = $store_routeros_data['password'];
                $store_routeros->connect = $store_routeros_data['connect'];
                $store_routeros->save();

                DB::commit();
                return redirect()->back()->with('success', 'Router has been successfully added!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('success', 'Error fetching router data!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RouterCredential $routerCredential)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RouterCredential $routerCredential)
    {
        return view();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RouterCredential $routerCredential)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RouterCredential $routerCredential)
    {
        //
    }
}
