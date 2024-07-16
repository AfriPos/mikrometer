<?php

namespace App\Http\Controllers;

use App\MyHelper\RouterosAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class routerSyncController extends Controller
{
    public function universalCoa($routerApi, $routerIp, $routerUsername, $routerPassword, $username)    {
        try {
            $routerApi->connect($routerIp, $routerUsername, $routerPassword);
          
            // Find the active PPPoE connection for the user
            $activeConnections = $routerApi->comm('/ppp/active/print', [
                '?name' => $username,
            ]);
           
            if (empty($activeConnections)) {
                $routerApi->disconnect();
                return ['success' => true, 'message' => 'No active connection found for the user'];
            }

            // Get the interface name of the active connection
            $interfaceName = $activeConnections[0]['name'];
            
            // Remove the active connection
            $routerApi->comm('/ppp/active/remove', [
                '.id' => $activeConnections[0]['.id'],
            ]);

            // Wait for a short period
            sleep(2);

            $routerApi->disconnect();

            return ['success' => true, 'message' => 'Connection reauthenticated successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to reauthenticate connection: ' . $e->getMessage()];
        }
    }
}
