<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            InstansiSeeder::class,
            JenisPelatihanSeeder::class,
            BentukPelatihanSeeder::class,
            SasaranSeeder::class,
            BangkomSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'testuser',
            'telepon' => '08123456789',
        ]);
    }
}
