<?php

namespace App\Http\Controllers;

use App\Models\RouterCredential;
use Illuminate\Http\Request;
use App\MyHelper\RouterosAPI;
use App\Http\Controllers\api\RouterosController;
use App\Models\locationsModel;
use App\Models\PoolModel;
use App\Models\radacct;
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
        $nas = RouterCredential::where('ip_address', $request->ip_address)->first();
        try {
            // Attempt to connect to the RouterOS
            if ($api->connect($request->ip_address, $nas->login, $nas->password)) {
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
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to connect to the router.',
                ]);
            }
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
        foreach ($routers as $router) {
            $router->clients_count = radacct::where('nasipaddress', $router->nasname)
                ->whereNull('acctstoptime')
                ->count();
        }
        return view('nas.index', compact('routers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locations = locationsModel::all();
        return view('nas.create', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate the incoming request data
            $validatedData = $request->validate([
                'nasname' => 'required|string|max:255',
                'shortname' => 'required|string|max:64',
                'type' => 'nullable|string|in:other,cisco,computone,livingston,max40xx,multitech,netserver,pathras,patton,portslave,tc,usrhiper,vasexpressaccess',
                'ports' => 'nullable|integer|min:0',
                'server' => 'nullable|string|max:255',
                'community' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:255',
                'username' => 'nullable|string|max:255',
                'password' => 'nullable|string|max:255',
                'radius_server_ip' => 'required|string|max:255',
                'secret' => 'nullable|string|min:6|max:255'
            ]);

            // Generate a 13-character secret with a balanced mix of small letters and numbers
            $letters = 'abcdefghijklmnopqrstuvwxyz';
            $numbers = '0123456789';
            $secret = '';
            for ($i = 0; $i < 13; $i++) {
                if ($i % 2 == 0) {
                    $secret .= $letters[rand(0, strlen($letters) - 1)];
                } else {
                    $secret .= $numbers[rand(0, strlen($numbers) - 1)];
                }
            }

            $validatedData['secret'] = $secret;
            $nas = RouterCredential::create($validatedData);

            DB::commit();
            // Call the restart server function
            $this->reloadRadiusServer();

            return redirect()->route('router.edit', ['nas' => $nas->id])->with('success', 'Router has been successfully added!');
            //     $routeros_db = RouterCredential::where('ip_address', $req_data['ip_address'])->get();

            //     if (count($routeros_db) > 0) {
            //         if (!$this->routerosController->check_routeros_connection($request->all())) {
            //             DB::rollBack();
            //             return redirect()->back()->with('error', 'RouterOS connection failed! check the administrator credentials!');
            //         }
            //     } else {

            //         $API = new RouterosAPI;
            //         $connection = $API->connect($request['ip_address'], $request['login'], $request['password']);
            //         if (!$connection) {
            //             DB::rollBack();
            //             return redirect()->back()->with('error', 'Error connecting to the router!');
            //         }
            //         // var_dump($API->comm('/system/identity/print'));
            //         $store_routeros_data = [
            //             'identity' => $API->comm('/system/identity/print')[0]['name'],
            //             'ip_address' => $request['ip_address'],
            //             'login' => $request['login'],
            //             'password' => $request['password'],
            //             'connect' => $connection

            //         ];
            //         $store_routeros = new RouterCredential;
            //         $store_routeros->identity = $store_routeros_data['identity'];
            //         $store_routeros->ip_address = $store_routeros_data['ip_address'];
            //         $store_routeros->login = $store_routeros_data['login'];
            //         $store_routeros->password = $store_routeros_data['password'];
            //         $store_routeros->connect = $store_routeros_data['connect'];
            //         $store_routeros->save();

            //         DB::commit();
            //         return redirect()->back()->with('success', 'Router has been successfully added!');
            //     }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            // return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RouterCredential $nas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RouterCredential $nas)
    {
        $pools = PoolModel::all();
        return view('nas.edit', compact('nas', 'pools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RouterCredential $nas)
    {
        try {
            // Implement database transaction
            DB::beginTransaction();

            // Define validation rules
            $rules = [
                'nasname' => 'required|string|max:255',
                'shortname' => 'required|string|max:64',
                'type' => 'nullable|string|in:other,cisco,computone,livingston,max40xx,multitech,netserver,pathras,patton,portslave,tc,usrhiper,vasexpressaccess',
                'ports' => 'nullable|integer|min:0',
                'server' => 'nullable|string|max:255',
                'community' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:255',
                'username' => 'nullable|string|max:255',
                'password' => 'nullable|string|max:255', // Password is nullable
                'radius_server_ip' => 'required|string|max:255',
                'secret' => 'nullable|string|max:255',
                'ip_pool' => 'nullable|string',
                'geo_data' => 'nullable|string|max:255',
            ];

            // Validate the request data
            $validator = Validator::make($request->all(), $rules);

            // // Check validation fails
            // if ($validator->fails()) {
            //     return redirect()->back()->with($validator)->withInput();
            // }

            // Get validated data
            $validatedData = $validator->validated();

            // Conditionally unset 'password' field if empty or not provided
            if (!isset($validatedData['password']) || empty($validatedData['password'])) {
                unset($validatedData['password']);
            }

            // Perform RouterOS configuration setup
            $ping = $this->ping($request->nasname, $latency = 0);
            if ($ping) {

                // Create an instance of the RouterosAPI class
                $api = new RouterosAPI();

                try {
                    // Connect to the RouterOS
                    if ($api->connect($request->nasname ?? $nas->nasname, $request->username ?? $nas->username, $request->password ?? $nas->password)) {                            // Set up RADIUS server
                        $networkIP = $this->getNetworkIp($request->ip_pool);
                        // Check if RADIUS server exists
                        $existing_radius = $api->comm('/radius/print', ['?address' => $nas->radius_server_ip]);
                        if (empty($existing_radius)) {
                            $radius_server = $api->comm('/radius/add', [
                                'address' => $nas->radius_server_ip,
                                'secret' => $nas->secret,
                                'service' => 'hotspot,ppp',
                                'timeout' => '300ms'
                            ]);
                        }

                        // Check if PPP profile exists
                        $existing_ppp_profile = $api->comm('/ppp/profile/print', ['?name' => 'MIKROMETER_RADIUS_PROFILE']);
                        if (empty($existing_ppp_profile)) {
                            $pppoe_profile = $api->comm('/ppp/profile/add', [
                                'name' => 'MIKROMETER_RADIUS_PROFILE',
                                'local-address' => $networkIP,
                            ]);
                        }

                        // Check if PPPoE server interface exists
                        $existing_pppoe_server = $api->comm('/interface/pppoe-server/server/print', ['?service-name' => 'MIKROMETER_RADIUS_SERVER']);
                        if (empty($existing_pppoe_server)) {
                            $pppoe_server = $api->comm('/interface/pppoe-server/server/add', [
                                'service-name' => 'MIKROMETER_RADIUS_SERVER',
                                'max-mtu' => '1480',
                                'max-mru' => '1480',
                                'authentication' => 'pap,chap,mschap1,mschap2',
                                'default-profile' => 'MIKROMETER_RADIUS_PROFILE',
                                'disabled' => 'no',
                            ]);
                        }

                        // Define an array of firewall rules
                        $firewall_rules = [
                            [
                                'chain' => 'forward',
                                'src-address-list' => 'MM-allowed-list',
                                'action' => 'accept',
                                'comment' => 'Allow traffic from MM-allowed-list'
                            ],
                            [
                                'chain' => 'forward',
                                'src-address-list' => 'MM-blocked-list',
                                'action' => 'drop',
                                'comment' => 'Drop traffic from MM-blocked-list'
                            ]
                        ];
                        // Check if firewall rules exist
                        foreach ($firewall_rules as $rule) {
                            $existing_rule = $api->comm('/ip/firewall/filter/print', [
                                '?chain' => $rule['chain'],
                                '?src-address-list' => $rule['src-address-list'],
                                '?action' => $rule['action']
                            ]);
                            if (empty($existing_rule)) {
                                $api->comm('/ip/firewall/filter/add', $rule);
                            }
                        }

                        // Disconnect from the router
                        $api->disconnect();

                        // Mark the NAS as configured
                        $nas->markAsConfigured();
                    } else {
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Failed to connect to the router!');
                    }
                } catch (\Throwable $e) {
                    DB::rollBack();
                    dd($e);
                    return redirect()->back()->with('error', 'Failed to configure RouterOS: ' . $e->getMessage());
                }
            }

            // Update model with validated data
            $nas->fill($validatedData);
            $nas->save();

            // Commit transaction
            DB::commit();
            // Call the restart server function
            $this->reloadRadiusServer();
            return redirect()->back()->with('success', 'Router updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return redirect()->back()->with('error', 'Failed to update Router record: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RouterCredential $nas)
    {
        // dd($nas);
        try {
            $nas->delete();
            return redirect()->back()->with('success', 'Router deleted successfully.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Failed to delete router.');
        }
    }

    public function pingInitialize(Request $request)
    {
        $ip = $request->input('ip');
        $latency = $this->ping($ip);

        if ($latency) {
            return response()->json(['status' => true, 'latency' => $latency]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    // ping an ip you provide
    private function ping($ip, $latency = 0)
    {
        $ping = exec("ping -n 1 $ip", $output, $status);

        if ($status === 0) {
            foreach ($output as $line) {
                if (strpos($line, 'time=') !== false) {
                    preg_match('/time[=<]\d+ms/', $line, $matches);
                    if (isset($matches[0])) {
                        $latency = (int) filter_var($matches[0], FILTER_SANITIZE_NUMBER_INT);
                    }
                    break;
                }
            }
            return $latency;
        } else {
            return false;
        }
    }

    // gets the network ip from a given subnet
    private function getNetworkIp($cidr)
    {
        list($ip, $prefixLength) = explode('/', $cidr);

        // Convert IP address to a long integer
        $ipLong = ip2long($ip);

        // Create the subnet mask
        $subnetMask = ~((1 << (32 - $prefixLength)) - 1);

        // Calculate the network address
        $network = $ipLong & $subnetMask;

        // Convert the network address back to an IP address
        $networkIp = long2ip($network);

        return $networkIp;
    }

    // reload the radius server
    public function reloadRadiusServer()
    {
        $command = "sudo systemctl restart freeradius";
        $output = [];
        $returnVar = 0;

        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            return response()->json(['status' => true, 'message' => 'RADIUS server reloaded successfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Failed to reload RADIUS server', 'error' => implode("\n", $output)]);
        }
    }

}
