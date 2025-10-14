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
            // Drop primary key on ulid if exists
            if (Schema::hasColumn('bentuk_pelatihans', 'ulid')) {
                $table->dropPrimary();
                $table->dropColumn('ulid');
            }

            // Add id as primary key if not exists
            if (!Schema::hasColumn('bentuk_pelatihans', 'id')) {
                $table->bigIncrements('id')->first();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bentuk_pelatihans', function (Blueprint $table) {
            //
        });
    }
};
