<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Bangkom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan kolom jadwal_codes ada di database
        if (!Schema::hasColumn('bangkoms', 'jadwal_codes')) {
            Schema::table('bangkoms', function (Blueprint $table) {
                $table->json('jadwal_codes')->nullable()->after('kuota');
            });
        }

        // Pastikan kolom status ada di database
        if (!Schema::hasColumn('bangkoms', 'status')) {
            Schema::table('bangkoms', function (Blueprint $table) {
                $table->string('status')->default('Draft')->after('jadwal_codes');
            });
        }

        $bangkoms = Bangkom::all();

        // Sample data yang hanya berisi kode jadwal (tanpa lokasi)
        $sampleCodes = [
            ['SPL-MHKZ', 'SPL-KLTQ'],
            ['SPL-PQRS', 'SPL-TUVX'],
            ['SPL-YZAB', 'SPL-CDEF'],
            ['SPL-GHIJ', 'SPL-KLMN'],
            ['SPL-OPQR', 'SPL-STUV'],
        ];

        // Sample status
        $sampleStatuses = ['Draft', 'Published', 'Cancelled'];

        foreach ($bangkoms as $index => $bangkom) {
            // Ambil sample kode berdasarkan index, atau buat baru jika index melebihi sample
            $jadwalCodes = $sampleCodes[$index % count($sampleCodes)] ?? [
                'SPL-' . str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT),
                'SPL-' . str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT)
            ];
            
            // Set status acak dari sample statuses
            $status = $sampleStatuses[array_rand($sampleStatuses)];
            
            // Use mass update to bypass mutator and avoid property modification error
            DB::table('bangkoms')
                ->where('id', $bangkom->id)
                ->update([
                    'jadwal_codes' => json_encode($jadwalCodes),
                    'status' => $status
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset data jadwal_codes dan status
        DB::table('bangkoms')->update(['jadwal_codes' => null, 'status' => 'Draft']);
        
        // Opsional: Hapus kolom jika tidak diperlukan lagi
        if (Schema::hasColumn('bangkoms', 'jadwal_codes')) {
            Schema::table('bangkoms', function (Blueprint $table) {
                $table->dropColumn('jadwal_codes');
            });
        }
        
        if (Schema::hasColumn('bangkoms', 'status')) {
            Schema::table('bangkoms', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};