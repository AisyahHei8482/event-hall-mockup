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
        User::factory()->create([
            'name' => 'Resort Admin',
            'email' => 'admin@savannahill.com.my',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Front Desk Staff',
            'email' => 'staff@savannahill.com.my',
            'role' => 'staff',
        ]);

        $this->call(FacilitySeeder::class);
        $this->call(EventHallSeeder::class);
        $this->call(WhiteHallSeeder::class);
    }
}
