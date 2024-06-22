<?php

namespace Database\Seeders;

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
        
        // Call additional seeders
        $this->call([
            RoutersSeeder::class,
            IpAddressesSeeder::class,
        ]);
    }
}
