<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('status_histories', function (Blueprint $table) {
            // Ubah kolom new_status menjadi nullable atau hapus jika tidak dipakai
            if (Schema::hasColumn('status_histories', 'new_status')) {
                $table->string('new_status')->nullable()->change();
            }
            
            // Atau jika kolom ini tidak dipakai sama sekali, hapus saja
            // $table->dropColumn('new_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('status_histories', function (Blueprint $table) {
            if (Schema::hasColumn('status_histories', 'new_status')) {
                $table->string('new_status')->nullable(false)->change();
            }
        });
    }
};