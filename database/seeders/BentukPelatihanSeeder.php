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
            ['bentuk' => 'Bentuk A', 'jalur' => 'Dalam Daerah', 'deskripsi' => 'Deskripsi Bentuk A'],
            ['bentuk' => 'Bentuk B', 'jalur' => 'Luar Daerah', 'deskripsi' => 'Deskripsi Bentuk B'],
            ['bentuk' => 'Bentuk C', 'jalur' => 'Nasional', 'deskripsi' => 'Deskripsi Bentuk C'],
        ];

        DB::table('bentuk_pelatihans')->insert($data);
    }
}
