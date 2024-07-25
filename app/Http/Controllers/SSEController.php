<?php

namespace App\Http\Controllers;

use App\Models\CustomerSubscriptionModel;
use App\Models\RouterCredential;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\MyHelper\RouterosAPI;
use Illuminate\Support\Facades\DB;

class SSEController extends Controller
{
    public function stream(Request $request)
    {
        // Extract query parameters
        $subscriptionId = $request->query('subscription_id');
        $subscription = CustomerSubscriptionModel::where('pppoe_login', $subscriptionId)->first();
        // dd($subscriptionId);
        $nas = RouterCredential::where('id', $subscription->nas_id)->first();
        $routerApi = new RouterosAPI();
        $routerIp = $nas->nasname; // Replace with your RouterOS IP address
        $routerUsername = $nas->username; // Replace with your RouterOS username
        $routerPassword = $nas->password; // Replace with your RouterOS password
        $username = $subscription->pppoe_login;
        $response = new StreamedResponse(function () use ($routerApi, $routerIp, $routerUsername, $routerPassword, $username) {
            $routerApi->connect($routerIp, $routerUsername, $routerPassword);
            $startTime = time();
            $maxExecutionTime = 55; // Set to slightly less than PHP's max_execution_time

            while (true) {
                if (time() - $startTime >= $maxExecutionTime) {
                    break; // Exit the loop before PHP's max execution time is reached
                }
                $data = $this->fetchBandwidth($routerApi, $username);
                echo "data: " . json_encode($data) . "\n\n";
                ob_flush();
                flush();
                sleep(1); // Adjust delay as needed

            }

            $routerApi->disconnect();
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');

        return $response;
    }

    private function fetchBandwidth($routerApi, $username)
    {
       

        $response = $routerApi->comm('/interface/monitor-traffic', [
            'interface' => '<pppoe-' . $username . '>', // Example interface name
            'once' => ''
        ]);

        // $bandwidthData = $routerApi->read();
        if (!empty($response)) {
            return [
                // 'response' => $response
                'timestamp' => now()->timestamp,
                'rxRate' => $response[0]['rx-bits-per-second'] ?? 0,
                'txRate' => $response[0]['tx-bits-per-second'] ?? 0,
            ];
        } else {
            return [];
        }
    }

}



