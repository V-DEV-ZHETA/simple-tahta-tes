<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BentukPelatihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Bentuk A'],
            ['name' => 'Bentuk B'],
            ['name' => 'Bentuk C'],
        ];

        DB::table('bentuk_pelatihans')->insert($data);
    }
}
