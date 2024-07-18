<?php
namespace App\Http\Controllers;

use App\Models\radacct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BandwidthController extends Controller
{
    public function getAverageBandwidth($username)
    {
        $hourly = DB::select("
            SELECT DATE_FORMAT(AcctStartTime, '%Y-%m-%d %H:00:00') AS hour,
            SUM(IF(AcctStopTime IS NULL, TIMESTAMPDIFF(SECOND, AcctStartTime, NOW()), AcctSessionTime) * AcctInputOctets / AcctSessionTime) / 3600 AS average_download,
            SUM(IF(AcctStopTime IS NULL, TIMESTAMPDIFF(SECOND, AcctStartTime, NOW()), AcctSessionTime) * AcctOutputOctets / AcctSessionTime) / 3600 AS average_upload
            FROM radacct
            WHERE UserName = ?
            GROUP BY hour
            ORDER BY hour DESC
        ", [$username]);

        $daily = DB::select("
            SELECT DATE_FORMAT(AcctStartTime, '%Y-%m-%d') AS day,
            SUM(IF(AcctStopTime IS NULL, TIMESTAMPDIFF(SECOND, AcctStartTime, NOW()), AcctSessionTime) * AcctInputOctets / AcctSessionTime) / 86400 AS average_download,
            SUM(IF(AcctStopTime IS NULL, TIMESTAMPDIFF(SECOND, AcctStartTime, NOW()), AcctSessionTime) * AcctOutputOctets / AcctSessionTime) / 86400 AS average_upload
            FROM radacct
            WHERE UserName = ?
            GROUP BY day
            ORDER BY day DESC
        ", [$username]);

        $weekly = DB::select("
            SELECT DATE_FORMAT(AcctStartTime, '%Y-%u') AS week,
            SUM(IF(AcctStopTime IS NULL, TIMESTAMPDIFF(SECOND, AcctStartTime, NOW()), AcctSessionTime) * AcctInputOctets / AcctSessionTime) / (86400 * 7) AS average_download,
            SUM(IF(AcctStopTime IS NULL, TIMESTAMPDIFF(SECOND, AcctStartTime, NOW()), AcctSessionTime) * AcctOutputOctets / AcctSessionTime) / (86400 * 7) AS average_upload
            FROM radacct
            WHERE UserName = ?
            GROUP BY week
            ORDER BY week DESC
        ", [$username]);

        $monthly = DB::select("
            SELECT DATE_FORMAT(AcctStartTime, '%Y-%m') AS month,
            SUM(IF(AcctStopTime IS NULL, TIMESTAMPDIFF(SECOND, AcctStartTime, NOW()), AcctSessionTime) * AcctInputOctets / AcctSessionTime) / (86400 * 30) AS average_download,
            SUM(IF(AcctStopTime IS NULL, TIMESTAMPDIFF(SECOND, AcctStartTime, NOW()), AcctSessionTime) * AcctOutputOctets / AcctSessionTime) / (86400 * 30) AS average_upload
            FROM radacct
            WHERE UserName = ?
            GROUP BY month
            ORDER BY month DESC
        ", [$username]);

        $yearly = DB::select("
            SELECT DATE_FORMAT(AcctStartTime, '%Y') AS year,
            SUM(IF(AcctStopTime IS NULL, TIMESTAMPDIFF(SECOND, AcctStartTime, NOW()), AcctSessionTime) * AcctInputOctets / AcctSessionTime) / (86400 * 365) AS average_download,
            SUM(IF(AcctStopTime IS NULL, TIMESTAMPDIFF(SECOND, AcctStartTime, NOW()), AcctSessionTime) * AcctOutputOctets / AcctSessionTime) / (86400 * 365) AS average_upload
            FROM radacct
            WHERE UserName = ?
            GROUP BY year
            ORDER BY year DESC
        ", [$username]);

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
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $username = $request->query('username');

        $dailyBandwidth = DB::table('radacct')
            ->where('username', $username)
            ->whereBetween('acctstarttime', [$startDate, $endDate])
            ->selectRaw('DATE(acctstarttime) as date, 
                         SUM(IFNULL(acctinputoctets, 0)) as total_download, 
                         SUM(IFNULL(acctoutputoctets, 0)) as total_upload,
                         SUM(IFNULL(acctinputoctets, 0) + IFNULL(acctoutputoctets, 0)) as total_bandwidth')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($dailyBandwidth);
    }
}
