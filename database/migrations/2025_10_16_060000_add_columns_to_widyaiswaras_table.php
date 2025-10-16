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
        Schema::table('widyaiswaras', function (Blueprint $table) {
            $table->string('nip')->nullable();
            $table->string('nama')->nullable();
            $table->string('satker')->nullable();
            $table->string('telpon')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('widyaiswaras', function (Blueprint $table) {
            $table->dropColumn(['nip', 'nama', 'satker', 'telpon', 'email', 'alamat']);
        });
    }
};
