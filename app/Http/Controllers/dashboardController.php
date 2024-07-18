<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\SSH;

class dashboardController extends Controller
{
    public function index()
    {
        $onlinecustomers = CustomerModel::where('status', 'active')->count();
        $newcustomers =  CustomerModel::where('status', 'new')->count();
        $customers =  CustomerModel::all()->count();
        $lastYearCustomers = CustomerModel::whereYear('created_at', now()->subYear()->year)->count();
        $lastMonthCustomers = CustomerModel::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        return view('dashboard', compact('onlinecustomers', 'newcustomers', 'customers', 'lastYearCustomers', 'lastMonthCustomers'));
    }

    // public function fetchSystemMetrics()
    // {
    //     $metrics = [];

    //     // Example commands to fetch system metrics
    //     $commands = [
    //         'uptime',       // Load averages
    //         'free -m',      // Memory usage
    //         'df -h',        // Disk usage
    //         'iostat',       // I/O statistics
    //         // Add more commands as needed
    //     ];

    //     // SSH into the server and execute commands
    //     SSH::into('production')->run($commands, function ($line) use (&$metrics) {
    //         // Process each line of output if necessary
    //         // Example: parse and store metrics
    //         $metrics[] = $line;
    //     });

    //     return $metrics;
    // }
}
