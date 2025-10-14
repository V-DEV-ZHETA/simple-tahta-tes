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
        $foreignKeyExists = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'bangkoms' AND COLUMN_NAME = 'bentuk_pelatihan_id' AND REFERENCED_TABLE_NAME = 'bentuk_pelatihans'");

        if (empty($foreignKeyExists)) {
            Schema::table('bangkoms', function (Blueprint $table) {
                $table->foreign('bentuk_pelatihan_id')->references('id')->on('bentuk_pelatihans');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bangkoms', function (Blueprint $table) {
            //
        });
    }
};
