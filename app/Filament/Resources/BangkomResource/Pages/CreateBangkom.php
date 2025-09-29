<?php

namespace App\Filament\Resources\BangkomResource\Pages;

use App\Filament\Resources\BangkomResource;
use App\Models\Bangkom;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateBangkom extends CreateRecord
{
    protected static string $resource = BangkomResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        do {
            $code = 'SPL-' . Str::upper(Str::random(4));
        } while (Bangkom::where('kode_kegiatan', $code)->exists());

        $data['kode_kegiatan'] = $code;

        // Generate jadwal_codes as flat array of unique random strings
        if (!isset($data['jadwal_codes']) || empty($data['jadwal_codes'])) {
            $jadwalCodes = [];
            for ($i = 0; $i < 2; $i++) {  // Default 2 codes
                do {
                    $jadwalCode = 'SPL-' . Str::upper(Str::random(4));
                } while (Bangkom::whereJsonContains('jadwal_codes', $jadwalCode)->exists());
                $jadwalCodes[] = $jadwalCode;
            }
            $data['jadwal_codes'] = $jadwalCodes;
        } elseif (is_array($data['jadwal_codes'])) {
            $jadwalCodes = [];
            foreach ($data['jadwal_codes'] as $item) {
                $code = is_array($item) && isset($item['code']) ? $item['code'] : (is_string($item) ? $item : null);
                if ($code) {
                    do {
                        if (!Bangkom::whereJsonContains('jadwal_codes', $code)->exists()) {
                            break;
                        }
                        $code = 'SPL-' . Str::upper(Str::random(4));
                    } while (true);
                    $jadwalCodes[] = $code;
                }
            }
            $data['jadwal_codes'] = $jadwalCodes;
        }

        // Default status if not set
        if (!isset($data['status'])) {
            $data['status'] = 'Draft';
        }

        return $data;
    }
}
