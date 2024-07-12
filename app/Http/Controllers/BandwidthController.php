<?php
namespace App\Http\Controllers;

use App\MyHelper\RouterosAPI;
use App\Models\radacct;
use Illuminate\Http\Request;

class BandwidthController extends Controller
{
    public function fetchBandwidth(Request $request, $customerId)
    {
        $routerIp = '192.168.100.218';
        $routerUsername = 'admin';
        $routerPassword = '12345';

        $api = new RouterosAPI();
        $interfaceName = "ether1";

        try {
            if ($api->connect($routerIp, $routerUsername, $routerPassword)) {
                $api->write('/interface/monitor-traffic', false);
                $api->write('=interface=' . $interfaceName, false);
                $api->write('=once=', true);
                $bandwidthData = $api->read();
                $api->disconnect();

                if (!empty($bandwidthData) && isset($bandwidthData[0]['tx-bits-per-second']) && isset($bandwidthData[0]['rx-bits-per-second'])) {
                    return response()->json([
                        'success' => true,
                        'tx_bits_per_second' => $bandwidthData[0]['tx-bits-per-second'],
                        'rx_bits_per_second' => $bandwidthData[0]['rx-bits-per-second'],
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'No bandwidth data available or missing keys in response.',
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
                'message' => 'Failed to connect to the router. ' . $th->getMessage(),
            ]);
        }
    }
    public function fetchaActiveSession(Request $request, $customerId){
            try {
                $userData = radacct::where('username', $customerId)
                    ->whereNull('acctstoptime')
                    ->orderBy('acctstarttime', 'desc')
                    ->first();

                if ($userData) {
                    return response()->json([
                        'success' => true,
                        'data' => $userData
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No data found for the given username.'
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while fetching user data: ' . $e->getMessage()
                ]);
            }
        }

    }
