<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoutersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nas')->insert([
            [
                'nasname' => '192.168.100.216',
                'shortname' => 'NAS1',
                'type' => 'other',
                'ports' => null,
                'secret' => 'admin',
                'server' => null,
                'community' => null,
                'description' => 'Main NAS for building 1',
            ],
            [
                'nasname' => '192.168.100.249',
                'shortname' => 'NAS2',
                'type' => 'cisco',
                'ports' => null,
                'secret' => 'admin',
                'server' => null,
                'community' => null,
                'description' => 'Backup NAS for building 1',
            ],
        ]);
    }
}
