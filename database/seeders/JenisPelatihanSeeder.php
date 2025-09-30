<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPelatihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Pelatihan A', 'deskripsi' => 'Deskripsi Pelatihan A'],
            ['name' => 'Pelatihan B', 'deskripsi' => 'Deskripsi Pelatihan B'],
            ['name' => 'Pelatihan C', 'deskripsi' => 'Deskripsi Pelatihan C'],
        ];

        DB::table('jenis_pelatihans')->insert($data);
    }
}
