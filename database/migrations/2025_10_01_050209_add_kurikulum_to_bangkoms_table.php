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
        Schema::table('bangkoms', function (Blueprint $table) {
            $table->json('kurikulum')->nullable()->after('telepon_panitia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bangkoms', function (Blueprint $table) {
            $table->dropColumn('kurikulum');
        });
    }
};
