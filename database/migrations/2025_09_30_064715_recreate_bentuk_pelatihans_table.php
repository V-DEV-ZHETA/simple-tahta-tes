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
        // Drop foreign key first
        Schema::table('bangkoms', function (Blueprint $table) {
            $table->dropForeign(['bentuk_pelatihan_id']);
        });

        Schema::dropIfExists('bentuk_pelatihans');

        Schema::create('bentuk_pelatihan', function (Blueprint $table) {
            $table->ulid('ulid')->primary();
            $table->string('jalur')->nullable();
            $table->string('bentuk')->nullable();
            $table->string('deskripsi')->nullable();
            $table->timestamps();
        });

        // Add foreign key back
        Schema::table('bangkoms', function (Blueprint $table) {
            $table->string('bentuk_pelatihan_id')->nullable()->change();
            $table->foreign('bentuk_pelatihan_id')->references('ulid')->on('bentuk_pelatihan')->cascadeOnDelete();
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
