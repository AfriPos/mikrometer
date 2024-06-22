<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IpAddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start = ip2long('10.10.20.0');
        $end = ip2long('10.10.20.255');

        for ($ip = $start; $ip <= $end; $ip++) {
            $ipAddress = long2ip($ip);

            // Determine if the current IP address is the first or last in the range
            $isUsable = true;
            if ($ip == $start || $ip == $end) {
                $isUsable = false;
            }

            DB::table('ip_addresses')->insert([
                'ip_address' => $ipAddress,
                'usable' => $isUsable,
            ]);
        }
    }
}
