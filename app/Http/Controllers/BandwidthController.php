<?php

namespace App\Http\Controllers;

use App\Models\dataUsage;
use App\Models\radacct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BandwidthController extends Controller
{
    public function getAverageBandwidth(Request $request)
    {
        $username = $request->query('username');
        $startYear = Carbon::createFromDate($request->query('start_year'))->startOfYear();
        $endYear = Carbon::createFromDate($request->query('end_year'))->endOfYear();

        $hourly = dataUsage::where('username', $username)
            ->whereBetween('period_start', [$startYear, $endYear])
            ->whereRaw('TIMESTAMPDIFF(SECOND, period_start, period_end) > 0') // Ensure period is valid
            ->selectRaw("DATE_FORMAT(period_start, '%Y-%m-%d %H:00:00') AS hour, 
                AVG(acctinputoctets / TIMESTAMPDIFF(SECOND, period_start, period_end)) AS average_upload, 
                AVG(acctoutputoctets / TIMESTAMPDIFF(SECOND, period_start, period_end)) AS average_download")
            ->groupBy(DB::raw("DATE_FORMAT(period_start, '%Y-%m-%d %H:00:00')"))
            ->orderBy('hour', 'DESC')
            ->having('average_upload', '>=', 0)
            ->having('average_download', '>=', 0)
            ->get();

        $daily = dataUsage::where('username', $username)
            ->whereBetween('period_start', [$startYear, $endYear])
            ->whereRaw('TIMESTAMPDIFF(SECOND, period_start, period_end) > 0') // Ensure period is valid
            ->selectRaw("DATE(period_start) AS day, 
                AVG(acctinputoctets / TIMESTAMPDIFF(SECOND, period_start, period_end)) AS average_upload, 
                AVG(acctoutputoctets / TIMESTAMPDIFF(SECOND, period_start, period_end)) AS average_download")
            ->groupBy(DB::raw("DATE(period_start)"))
            ->orderBy('day', 'DESC')
            ->having('average_upload', '>=', 0)
            ->having('average_download', '>=', 0)
            ->get();

        $weekly = dataUsage::where('username', $username)
            ->whereBetween('period_start', [$startYear, $endYear])
            ->whereRaw('TIMESTAMPDIFF(SECOND, period_start, period_end) > 0') // Ensure period is valid
            ->selectRaw("DATE_FORMAT(period_start, '%Y-%u') AS week, 
                AVG(acctinputoctets / TIMESTAMPDIFF(SECOND, period_start, period_end)) AS average_upload, 
                AVG(acctoutputoctets / TIMESTAMPDIFF(SECOND, period_start, period_end)) AS average_download")
            ->groupBy(DB::raw("DATE_FORMAT(period_start, '%Y-%u')"))
            ->orderBy('week', 'DESC')
            ->having('average_upload', '>=', 0)
            ->having('average_download', '>=', 0)
            ->get();

        $monthly = dataUsage::where('username', $username)
            ->whereBetween('period_start', [$startYear, $endYear])
            ->whereRaw('TIMESTAMPDIFF(SECOND, period_start, period_end) > 0') // Ensure period is valid
            ->selectRaw("DATE_FORMAT(period_start, '%Y-%m') AS month, 
                AVG(acctinputoctets / TIMESTAMPDIFF(SECOND, period_start, period_end)) AS average_upload, 
                AVG(acctoutputoctets / TIMESTAMPDIFF(SECOND, period_start, period_end)) AS average_download")
            ->groupBy(DB::raw("DATE_FORMAT(period_start, '%Y-%m')"))
            ->orderBy('month', 'DESC')
            ->having('average_upload', '>=', 0)
            ->having('average_download', '>=', 0)
            ->get();

        $yearly = dataUsage::where('username', $username)
            ->whereBetween('period_start', [$startYear, $endYear])
            ->whereRaw('TIMESTAMPDIFF(SECOND, period_start, period_end) > 0') // Ensure period is valid
            ->selectRaw("YEAR(period_start) AS year, 
                AVG(acctinputoctets / TIMESTAMPDIFF(SECOND, period_start, period_end)) AS average_upload, 
                AVG(acctoutputoctets / TIMESTAMPDIFF(SECOND, period_start, period_end)) AS average_download")
            ->groupBy(DB::raw("YEAR(period_start)"))
            ->orderBy('year', 'DESC')
            ->having('average_upload', '>=', 0)
            ->having('average_download', '>=', 0)
            ->get();

        return response()->json([
            'hourly' => $hourly,
            'daily' => $daily,
            'weekly' => $weekly,
            'monthly' => $monthly,
            'yearly' => $yearly
        ]);
    }

    public function getTotalDailyBandwidth(Request $request)
    {
        $startDate = Carbon::parse($request->query('start_date'));
        $endDate = Carbon::parse($request->query('end_date'));
        $username = $request->query('username');

        $data = dataUsage::where('username', $username)
            ->whereDate('period_start', '>=', $startDate->startOfDay())
            ->whereDate('period_start', '<=', $endDate->endOfDay())
            ->selectRaw('DATE(period_start) as date, SUM(acctinputoctets) as total_upload, SUM(acctoutputoctets) as total_download')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $data;
    }
}
