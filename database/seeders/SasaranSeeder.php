<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SasaranSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Sasaran A'],
            ['name' => 'Sasaran B'],
            ['name' => 'Sasaran C'],
        ];

        DB::table('sasarans')->insert($data);
    }
}
