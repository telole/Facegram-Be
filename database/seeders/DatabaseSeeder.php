<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'username' => 'Test User',
            'bio' => 'test@example.com',
            'password'=> bcrypt('123'),
            'full_name' => 'Madacascar',
            'is_private' => 1,
            'created_at'=> \Carbon\Carbon::now(),
        ]);
    }
}
