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
            ['bentuk' => 'Bentuk A', 'jalur' => 'Klasikal', 'deskripsi' => 'Deskripsi Bentuk A'],
            ['bentuk' => 'Bentuk B', 'jalur' => 'Non Klasikal', 'deskripsi' => 'Deskripsi Bentuk B'],
            ['bentuk' => 'Bentuk C', 'jalur' => 'Klasikal', 'deskripsi' => 'Deskripsi Bentuk C'],
        ];

        DB::table('bentuk_pelatihans')->insert($data);
    }
}
