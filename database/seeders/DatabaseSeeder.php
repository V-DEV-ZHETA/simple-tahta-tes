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

        // Skip creating test user to avoid duplicate email error
    }
}
