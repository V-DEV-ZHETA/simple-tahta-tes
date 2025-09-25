<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstansiSeeder extends Seeder
{
    public function run(): void
    {
        $instansis = [
            ['name' => 'Instansi A', 'description' => 'Deskripsi Instansi A'],
            ['name' => 'Instansi B', 'description' => 'Deskripsi Instansi B'],
            ['name' => 'Instansi C', 'description' => 'Deskripsi Instansi C'],
        ];

        DB::table('instansis')->insert($instansis);
    }
}
