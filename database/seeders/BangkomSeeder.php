<?php

namespace Database\Seeders;

use App\Models\Bangkom;
use App\Models\Instansi;
use App\Models\JenisPelatihan;
use App\Models\BentukPelatihan;
use App\Models\Sasaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BangkomSeeder extends Seeder
{
    public function run(): void
    {
        // Leave Bangkom table empty as requested
        $this->command->info('BangkomSeeder completed. No records added.');
    }


}
