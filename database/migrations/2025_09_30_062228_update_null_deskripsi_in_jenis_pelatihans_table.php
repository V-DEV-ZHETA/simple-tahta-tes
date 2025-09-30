<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('jenis_pelatihans')->whereNull('deskripsi')->update(['deskripsi' => 'Deskripsi belum diisi']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse action needed as this is a data update
    }
};
