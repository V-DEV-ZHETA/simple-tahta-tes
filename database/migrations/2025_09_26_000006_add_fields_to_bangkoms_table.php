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
            $table->date('tanggal_mulai')->nullable()->after('sasaran_id');
            $table->date('tanggal_berakhir')->nullable()->after('tanggal_mulai');
            $table->string('tempat')->nullable()->after('tanggal_berakhir');
            $table->text('alamat')->nullable()->after('tempat');
            $table->integer('kuota')->nullable()->after('alamat');
            $table->string('nama_panitia')->nullable()->after('kuota');
            $table->string('telepon_panitia')->nullable()->after('nama_panitia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bangkoms', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_mulai',
                'tanggal_berakhir',
                'tempat',
                'alamat',
                'kuota',
                'nama_panitia',
                'telepon_panitia',
            ]);
        });
    }
};
