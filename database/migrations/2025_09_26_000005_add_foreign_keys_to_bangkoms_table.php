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
            $table->foreignId('instansi_id')->nullable()->constrained('instansis')->cascadeOnDelete()->after('id');
            $table->string('unit_kerja')->nullable()->after('instansi_id');
            $table->string('nama_kegiatan')->nullable()->after('unit_kerja');
            $table->foreignId('jenis_pelatihan_id')->nullable()->constrained('jenis_pelatihans')->cascadeOnDelete()->after('nama_kegiatan');
            $table->foreignId('bentuk_pelatihan_id')->nullable()->constrained('bentuk_pelatihans')->cascadeOnDelete()->after('jenis_pelatihan_id');
            $table->foreignId('sasaran_id')->nullable()->constrained('sasarans')->cascadeOnDelete()->after('bentuk_pelatihan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bangkoms', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->dropForeign(['jenis_pelatihan_id']);
            $table->dropForeign(['bentuk_pelatihan_id']);
            $table->dropForeign(['sasaran_id']);
            $table->dropColumn([
                'instansi_id',
                'unit_kerja',
                'nama_kegiatan',
                'jenis_pelatihan_id',
                'bentuk_pelatihan_id',
                'sasaran_id',
            ]);
        });
    }
};
