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
        Schema::table('status_histories', function (Blueprint $table) {
            // Tambahkan kolom jika belum ada
            if (!Schema::hasColumn('status_histories', 'bangkom_id')) {
                $table->foreignId('bangkom_id')->after('id')->constrained('bangkoms')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('status_histories', 'status_sebelum')) {
                $table->string('status_sebelum')->nullable()->after('bangkom_id');
            }
            
            if (!Schema::hasColumn('status_histories', 'status_menjadi')) {
                $table->string('status_menjadi')->nullable()->after('status_sebelum');
            }
            
            if (!Schema::hasColumn('status_histories', 'users_id')) {
                $table->foreignId('users_id')->nullable()->after('status_menjadi')->constrained('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('status_histories', 'oleh')) {
                $table->string('oleh')->nullable()->after('users_id');
            }
            
            if (!Schema::hasColumn('status_histories', 'catatan')) {
                $table->text('catatan')->nullable()->after('oleh');
            }
            
            // Tambahkan index
            if (!Schema::hasIndex('status_histories', ['bangkom_id'])) {
                $table->index('bangkom_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('status_histories', function (Blueprint $table) {
            $table->dropColumn(['status_sebelum', 'status_menjadi', 'users_id', 'oleh', 'catatan']);
        });
    }
};