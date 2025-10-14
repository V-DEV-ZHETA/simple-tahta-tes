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
        Schema::table('bentuk_pelatihans', function (Blueprint $table) {
            if (Schema::hasColumn('bentuk_pelatihans', 'jalur')) {
                $table->dropColumn('jalur');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bentuk_pelatihans', function (Blueprint $table) {
            if (!Schema::hasColumn('bentuk_pelatihans', 'jalur')) {
                $table->string('jalur')->nullable()->after('bentuk');
            }
        });
    }
};
