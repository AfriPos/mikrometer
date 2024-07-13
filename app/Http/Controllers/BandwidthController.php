<?php
namespace App\Http\Controllers;

use App\MyHelper\RouterosAPI;
use App\Models\radacct;
use Illuminate\Http\Request;

class BandwidthController extends Controller
{
    public function fetchBandwidth(Request $request, $customerId)
    {
        $routerIp = '192.168.100.215';
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
    public function fetchActiveSession($clientId)
    {
        try {
            $activeSession = radacct::where('username', $clientId)
                ->whereNull('acctstoptime')
                ->latest('acctstarttime')
                ->first();

            if ($activeSession) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'session_id' => $activeSession->radacctid,
                        'start_time' => $activeSession->acctstarttime,
                        'session_time' => $activeSession->acctsessiontime,
                        'input_octets' => $activeSession->acctinputoctets,
                        'output_octets' => $activeSession->acctoutputoctets,
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No active session found for the client.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active session. ' . $e->getMessage(),
            ]);
        }
    }


    }
