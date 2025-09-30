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
            $table->foreign('bentuk_pelatihan_id')->references('id')->on('bentuk_pelatihan');
        });
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
