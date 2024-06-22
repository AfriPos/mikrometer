<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\RouterOs;
use App\MyHelper\RouterosAPI;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;

class RouterosController extends Controller
{
    public $API = [], $routeros_data = [], $connection;
    public function test_api()
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Welcome to RouterOs API'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching router data'
            ]);
        }
    }

    public function store_routeros($data)
    {
        $API = new RouterosAPI;
        $connection = $API->connect($data['ip_address'], $data['login'], $data['password']);
        if (!$connection) {
            return response()->json(['error' => true, 'message' => 'Error connecting to the router'], 404);
        }
        // var_dump($API->comm('/system/identity/print'));
        $store_routeros_data = [
            'identity' => $API->comm('/system/identity/print')[0]['name'],
            'ip_address' => $data['ip_address'],
            'login' => $data['login'],
            'password' => $data['password'],
            'connect' => $connection

        ];
        $store_routeros = new RouterOs;
        $store_routeros->identity = $store_routeros_data['identity'];
        $store_routeros->ip_address = $store_routeros_data['ip_address'];
        $store_routeros->login = $store_routeros_data['login'];
        $store_routeros->password = $store_routeros_data['password'];
        $store_routeros->connect = $store_routeros_data['connect'];
        $store_routeros->save();

        return response()->json([
            'success' => true,
            'message' => 'Login credentials added'
        ]);
    }
    public function routeros_connection(Request $request)
    {

        try {
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

            $routeros_db = RouterOs::where('ip_address', $req_data['ip_address'])->get();

            if (count($routeros_db) > 0) {
                if ($this->check_routeros_connection($request->all())) :
                    return response()->json([
                        'connect' => true,
                        'message' => 'RouterOS connection established',
                        'routeros_data' => $this->routeros_data
                    ]);
                else :
                    return response()->json([
                        'error' => true,
                        'message' => 'RouterOS connection failed! check the administrator credentials',
                    ]);
                endif;
            } else {
                return $this->store_routeros($request->all());
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching router data,' . $e->getMessage(),
            ]);
        }
    }

    public function check_routeros_connection($data)
    {
        $routeros_db = RouterOs::where('ip_address', $data['ip_address'])->get();
        if (count($routeros_db) > 0) :
            $API = new RouterosAPI;
            $connection = $API->connect($routeros_db[0]['ip_address'], $routeros_db[0]['login'], $routeros_db[0]['password']);

            if (!$connection)
                return false;

            $this->API = $API;
            $this->connection = $connection;
            $this->routeros_data = [
                'identity' => $this->API->comm('/system/identity/print')[0]['name'],
                'ip_address' => $routeros_db[0]['ip_address'],
                'login' => $routeros_db[0]['login'],
                'connect' => $connection
            ];
            return true;
        endif;
    }

    public function set_interface(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ip_address' => 'required',
                'id' => 'required',
                'interface' => 'required',
                'name' => 'required'
            ]);

            if ($validator->fails())
                return response()->json($validator->errors(), 404);

            if ($this->check_routeros_connection($request->all())) :
                $interface_lists = $this->API->comm('/interface/print');
                $find_interface = array_search($request->name, array_column($interface_lists, 'name'));

                // var_dump($find_interface); die;

                if (!$find_interface) :
                    $set_interface = $this->API->comm('/interface/set', [
                        '.id' => "*$request->id",
                        'name' => "$request->name"
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => "Successfully set interface from : $request->interface, to : $request->name",
                        'interface_lists' => $this->API->comm('/interface/print')
                    ]);

                else :
                    return response()->json([
                        'success' => false,
                        'message' => "Interface name : $request->name, with .id : *$request->id has already been taken from routeros",
                        'interface_lists' => $this->API->comm('/interface/print')
                    ]);
                endif;

            endif;
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetch data Routeros API, ' . $e->getMessage()
            ]);
        }
    }
    public function add_ip_pool(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'ip_address' => 'required',
                'name' => 'required',
                'ranges' => 'required'
            ]);

            if ($validator->fails())
                return response()->json($validator->errors(), 404);

            // Check the RouterOS connection
            if ($this->check_routeros_connection($request->all())) :
                // Get the list of IP pools
                $pool_lists = $this->API->comm('/ip/pool/print');
                $find_pool = array_search($request->name, array_column($pool_lists, 'name'));

                // If the pool does not exist, add it
                if ($find_pool === false) :
                    $add_pool = $this->API->comm('/ip/pool/add', [
                        'name' => $request->name,
                        'ranges' => $request->ranges
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => "Successfully added IP pool: $request->name with ranges: $request->ranges",
                        'pool_lists' => $this->API->comm('/ip/pool/print')
                    ]);
                else :
                    return response()->json([
                        'success' => false,
                        'message' => "IP pool name: $request->name already exists",
                        'pool_lists' => $this->API->comm('/ip/pool/print')
                    ]);
                endif;

            endif;
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching data from RouterOS API, ' . $e->getMessage()
            ]);
        }
    }

    public function create_pppoe(Request $request){
        // Convert rates to kbps
        $rate_download = $this->convertToKbps($request->rate_download, $request->rate_download_unit);
        $rate_upload = $this->convertToKbps($request->rate_upload, $request->rate_upload_unit);

        // Connect to the RouterOS
        if ($this->check_routeros_connection($request->all())) {
            // Create the PPPoE server interface
            $this->API->comm('/interface/pppoe-server/server/add', [
                'service-name' => $request->service_name,
                'interface' => $request->interface,
                'max-mtu' => '1480',
                'max-mru' => '1480',
                'authentication' => 'pap,chap,mschap1,mschap2',
                'default-profile' => $request->profile_name,
                'enabled' => 'yes',
            ]);

            // Create the PPP profile with additional parameters
            $this->API->comm('/ppp/profile/add', [
                'name' => $request->profile_name,
                'local-address' => $request->local_address,
                'remote-address' => $request->remote_address_pool,
                'rate-limit' => $rate_download/$rate_upload,
                'burst-limit' => $request->burst_limit,
                'burst-threshold' => $request->burst_threshold,
                'burst-time' => $request->burst_time,
                // 'priority' => $request->priority,
                'limit-at' => $request->limit_at,
            ]);

            // Disconnect from the router
            $this->API->disconnect();

            return response()->json([
                'success' => false,
                'message' => 'PPPoE service created successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to the router.'
            ]);
        }
    }

    /**
     * Convert rate to kbps
     *
     * @param float $rate
     * @param string $unit
     * @return int
     */
    private function convertToKbps($rate, $unit)
    {
        switch ($unit) {
            case 'mbps':
                return $rate * 1000;
            case 'gbps':
                return $rate * 1000000;
            case 'kbps':
            default:
                return $rate;
        }
    }
}
