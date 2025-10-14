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

        $bangkoms = Bangkom::withoutGlobalScopes()->get();

        // Sample status
        $sampleStatuses = ['Draft', 'Published', 'Cancelled'];

        // Collect all existing jadwal_codes to avoid duplicates
        $existingCodes = [];
        foreach ($bangkoms as $bangkom) {
            if ($bangkom->jadwal_codes) {
                $codes = is_array($bangkom->jadwal_codes) ? $bangkom->jadwal_codes : json_decode($bangkom->jadwal_codes, true);
                if (is_array($codes)) {
                    $existingCodes = array_merge($existingCodes, $codes);
                }
            }
        }
        $existingCodes = array_unique($existingCodes);

        foreach ($bangkoms as $bangkom) {
            $jadwalCodes = [];
            for ($i = 0; $i < 2; $i++) {
                do {
                    $jadwalCode = 'SPL-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
                } while (in_array($jadwalCode, $existingCodes) || in_array($jadwalCode, $jadwalCodes));
                $jadwalCodes[] = $jadwalCode;
                $existingCodes[] = $jadwalCode;
            }

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
        // Hapus data jadwal_codes dan status hanya jika kolom ada
        $updateData = [];
        if (Schema::hasColumn('bangkoms', 'jadwal_codes')) {
            $updateData['jadwal_codes'] = null;
        }
        if (Schema::hasColumn('bangkoms', 'status')) {
            $updateData['status'] = null;
        }
        if (!empty($updateData)) {
            Bangkom::withoutGlobalScopes()->update($updateData);
        }

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