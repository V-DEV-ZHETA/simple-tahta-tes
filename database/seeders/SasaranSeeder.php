<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SasaranSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Sasaran A', 'deskripsi' => 'Deskripsi Sasaran A'],
            ['name' => 'Sasaran B', 'deskripsi' => 'Deskripsi Sasaran B'],
            ['name' => 'Sasaran C', 'deskripsi' => 'Deskripsi Sasaran C'],
        ];

        DB::table('sasarans')->insert($data);
    }
}
