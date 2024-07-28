<?php

namespace Database\Seeders;

use App\Models\CustomerModel;
use App\Models\radgroupreply;
use App\Models\radreply;
use App\Models\radusergroup;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'craigjohnson2402@gmail.com',
            'password' => 'password@123'
        ]);

        // group reply seeders
        radgroupreply::create([
            'groupname' => 'disabled',
            'attribute' => 'Mikrotik-Address-List',
            'op' => '=',
            'value' => 'MM-blocked-list'
        ]);

        radusergroup::created([
            'username' => 'disabled',
            'groupname' => 'disabled',
            'priority' => '1'
        ]);
        

        



        // Adjust the number inside the loop as per your requirement (1000 in this case)
        for ($i = 0; $i < 2000; $i++) {
            CustomerModel::create([
                'status' => 'new',
                'name' => 'Name ' . ($i + 1),
                'email' => 'email' . ($i + 1) . '@example.com',
                'phone' => '1234567890',
                'portal_login' => 'login' . ($i + 1),
                'portal_password' => 'password' . ($i + 1),
                'service_type' => 'Type ' . ($i + 1),
                'category' => 'Category ' . ($i + 1),
                'billing_email' => 'billing' . ($i + 1) . '@example.com',
                'mpesa_phone' => '0987654321',
                'dob' => now()->subYears(20 + $i)->format('Y-m-d'),
                'id_number' => 'ID' . ($i + 1),
                'street' => 'Street ' . ($i + 1),
                'zip_code' => '12345',
                'city' => 'City ' . ($i + 1),
                'geo_data' => 'Latitude,Longitude',
            ]);
        }

        // Call additional seeders
        $this->call([
            // RoutersSeeder::class,
            // IpAddressesSeeder::class,
            MikrotikPPPoEPermissionSeeder::class,
        ]);
    }
}
