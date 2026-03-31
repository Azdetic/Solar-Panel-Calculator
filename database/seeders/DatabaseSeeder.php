<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@solarsmart.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Demo User
        User::factory()->create([
            'name' => 'Demo User',
            'email' => 'user@solarsmart.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->call([
            TariffSeeder::class,
        ]);
    }
}
