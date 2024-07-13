<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\MyHelper\RouterosAPI;

class SSEController extends Controller
{
    public function stream()
    {
        $routerApi = new RouterosAPI();
        $routerIp = '192.168.100.216'; // Replace with your RouterOS IP
        $routerUsername = 'user1'; // Replace with your RouterOS username
        $routerPassword = '123456'; // Replace with your RouterOS password

        $response = new StreamedResponse(function () use ($routerApi, $routerIp, $routerUsername, $routerPassword) {
            $routerApi->connect($routerIp, $routerUsername, $routerPassword);

            while (true) {
                $data = $this->fetchBandwidth($routerApi);
                echo "data: " . json_encode($data) . "\n\n";
                ob_flush();
                flush();
                sleep(1); // Adjust delay as needed
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');

        return $response;
    }

    private function fetchBandwidth($routerApi)
    {
        $response = $routerApi->comm('/interface/monitor-traffic', [
            'interface' => 'ether1', // Example interface name
            'once' => ''
        ]);

        // $bandwidthData = $routerApi->read();
        if (!empty($response)) {
            return [
                // 'response' => $response[0]['rx-bits-per-second']
                'timestamp' => now()->timestamp,
                'rxRate' => $response[0]['rx-bits-per-second'] ?? 0,
                'txRate' => $response[0]['tx-bits-per-second'] ?? 0,
            ];
        } else {
            return [];
        }
    }

}
