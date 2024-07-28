<?php

namespace App\Http\Controllers;

use App\Models\hotspotModel;
use App\Models\radgroupreply;
use App\MyHelper\RouterosAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class hotspotController extends Controller
{
    /**
     * Display the form for creating a new hotspot plan.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('hotspot.create');
    }

    /**
     * Store a newly created hotspot plan in storage and on the MikroTik router.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'plan_name' => 'required|string|max:255',
                'plan_price' => 'required|numeric|min:0',
                'data_limit' => 'required|numeric|min:0',
                'data_limit_unit' => 'required|in:MB,GB',
                'validity' => 'required|numeric|min:0',
                'validity_unit' => 'required|in:hours,days,months',
                'speed_limit' => 'required|numeric|min:0',
                'speed_limit_unit' => 'required|in:k,M',
                'simultaneous_use' => 'required|in:yes,no',
            ]);

            // Create hotspot plan in database
            hotspotModel::create($validatedData);

            // Convert data limit to bytes
            $dataLimit = $validatedData['data_limit'] * ($validatedData['data_limit_unit'] === 'GB' ? 1024 * 1024 * 1024 : 1024 * 1024);

            // Convert validity to seconds
            switch ($validatedData['validity_unit']) {
                case 'hours':
                    $validity = $validatedData['validity'] * 3600;
                    break;
                case 'days':
                    $validity = $validatedData['validity'] * 86400;
                    break;
                case 'months':
                    $validity = $validatedData['validity'] * 2592000;
                    break;
            }

            // Convert speed limit to bps
            $speedLimit = $validatedData['speed_limit'] * ($validatedData['speed_limit_unit'] === 'M' ? 1048576 : 1024);

            // Simultaneous use
            $simultaneousUse = $validatedData['simultaneous_use'] === 'yes' ? 1 : 0;

            // Create hotspot user profile on MikroTik
            $groupname = $validatedData['plan_name'];
            DB::table('radgroupreply')->insert([
                ['groupname' => $groupname, 'attribute' => 'Session-Timeout', 'op' => ':=', 'value' => $validity],
                ['groupname' => $groupname, 'attribute' => 'Idle-Timeout', 'op' => ':=', 'value' => $validity],
                ['groupname' => $groupname, 'attribute' => 'WISPr-Bandwidth-Max-Down', 'op' => ':=', 'value' => $speedLimit],
                ['groupname' => $groupname, 'attribute' => 'WISPr-Bandwidth-Max-Up', 'op' => ':=', 'value' => $speedLimit],
            ]);

            DB::commit();

            return redirect()->route('hotspot.bundles')->with('success', 'Hotspot plan created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('hotspot.bundles')->with('error', 'Failed to create hotspot plan. Please try again.');
        }
    }

    /**
     * Display a listing of the hotspot bundles.
     *
     * @return \Illuminate\View\View
     */
    public function bundles()
    {
        return view('hotspot.bundles');
    }

    /**
     * Handle the purchase of a hotspot bundle.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function purchase()
    {
        try {
            // Store credentials in session
            $username = 'user1'; // Your logic to get the username
            $password = 'password123'; // Your logic to get the password
            session(['hotspot_credentials' => compact('username', 'password')]);

            // Redirect to secure endpoint that handles final redirection
            return redirect()->route('hotspot.redirect');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to process purchase']);
        }
    }

    public function redirect()
    {
        $credentials = session('hotspot_credentials');

        if (!$credentials) {
            return redirect()->route('purchase.page')->withErrors(['error' => 'No credentials found']);
        }

        $username = $credentials['username'];
        $password = $credentials['password'];

        return redirect("http://10.0.0.1/login?username=$username&password=$password");
    }
}
