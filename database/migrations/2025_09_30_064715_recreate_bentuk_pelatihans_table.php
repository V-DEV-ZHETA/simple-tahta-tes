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
        // Drop foreign key constraint first
        Schema::table('bangkoms', function (Blueprint $table) {
            if (Schema::hasColumn('bangkoms', 'bentuk_pelatihan_id')) {
                $table->dropForeign(['bentuk_pelatihan_id']);
            }
        });

        Schema::dropIfExists('bentuk_pelatihans');

        Schema::create('bentuk_pelatihans', function (Blueprint $table) {
            $table->id();
            $table->string('jalur')->nullable();
            $table->string('bentuk')->nullable();
            $table->string('deskripsi')->nullable();
            $table->timestamps();
        });

        // Add foreign key back
        Schema::table('bangkoms', function (Blueprint $table) {
            $table->unsignedBigInteger('bentuk_pelatihan_id')->nullable()->change();
            $table->foreign('bentuk_pelatihan_id')->references('id')->on('bentuk_pelatihans')->cascadeOnDelete();
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
