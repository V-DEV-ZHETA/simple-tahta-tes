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
        Schema::create('coachings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_coaching');
            $table->foreignId('instansi_id')->constrained('instansis')->onDelete('cascade');
            $table->foreignId('widyaiswara_id')->constrained('widyaiswaras')->onDelete('cascade');
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->integer('kuota');
            $table->enum('status', ['draft', 'published', 'cancelled'])->default('draft');
            $table->text('deskripsi')->nullable();
            $table->text('persyaratan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coachings');
    }
};
