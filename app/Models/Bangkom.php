<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Instansi;
use App\Models\JenisPelatihan;
use App\Models\BentukPelatihan;
use App\Models\Sasaran;

class Bangkom extends Model
{
    protected $fillable = [
        'instansi_id',
        'unit_kerja',
        'nama_kegiatan',
        'kode_kegiatan',
        'jenis_pelatihan_id',
        'bentuk_pelatihan_id',
        'sasaran_id',
        'tanggal_mulai',
        'tanggal_berakhir',
        'tempat',
        'alamat',
        'kuota',
        'nama_panitia',
        'telepon_panitia',
        'jadwal_codes',
        'status',
    ];

    protected $casts = [
        'jadwal_codes' => 'array',
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'kuota' => 'integer',
    ];

    // Accessor untuk mendapatkan daftar kode jadwal sebagai string yang dipisahkan koma
    public function getJadwalCodesListAttribute()
    {
        if (is_null($this->jadwal_codes)) {
            return '';
        }
        
        if (is_array($this->jadwal_codes)) {
            $codes = collect($this->jadwal_codes)
                ->filter(function ($item) {
                    // Untuk format baru: string langsung
                    if (is_string($item)) {
                        return !empty($item);
                    }
                    // Untuk format lama: array dengan key 'code'
                    if (is_array($item) && isset($item['code'])) {
                        return !empty($item['code']);
                    }
                    return false;
                })
                ->map(function ($item) {
                    // Untuk format baru: string langsung
                    if (is_string($item)) {
                        return $item;
                    }
                    // Untuk format lama: array dengan key 'code'
                    if (is_array($item) && isset($item['code'])) {
                        return $item['code'];
                    }
                    return '';
                });
                
            return $codes->join(', ');
        }
        
        return $this->jadwal_codes;
    }

    // Mutator untuk memastikan format jadwal_codes konsisten
    public function setJadwalCodesAttribute($value)
    {
        if (is_array($value)) {
            // Normalisasi data untuk memastikan format yang konsisten
            $normalized = collect($value)->map(function ($item) {
                // Jika item adalah string, gunakan langsung
                if (is_string($item)) {
                    return $item;
                }
                // Jika item adalah array dengan key 'code', gunakan nilai 'code'
                if (is_array($item) && isset($item['code'])) {
                    return $item['code'];
                }
                // Jika format tidak dikenal, kembalikan string kosong
                return '';
            })->filter()->all(); // Hapus item kosong
            
            return json_encode($normalized);
        } else {
            return $value;
        }
    }

    // Method untuk menambahkan kode jadwal baru
    public function addJadwalCode($code = null)
    {
        $codes = $this->jadwal_codes ?? [];
        
        if (is_null($code)) {
            $code = 'SPL-' . str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        }
        
        $codes[] = $code;
        $this->jadwal_codes = $codes;
        $this->save();
        
        return $code;
    }

    // Method untuk menghapus kode jadwal
    public function removeJadwalCode($index)
    {
        $codes = $this->jadwal_codes ?? [];
        
        if (isset($codes[$index])) {
            unset($codes[$index]);
            $this->jadwal_codes = array_values($codes); // Reindex array
            $this->save();
            return true;
        }
        
        return false;
    }

    // Relasi dengan model lain
    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function jenisPelatihan()
    {
        return $this->belongsTo(JenisPelatihan::class);
    }

    public function bentukPelatihan()
    {
        return $this->belongsTo(BentukPelatihan::class);
    }

    public function sasaran()
    {
        return $this->belongsTo(Sasaran::class);
    }
}