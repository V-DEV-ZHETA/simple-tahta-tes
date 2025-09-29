<?php

namespace Database\Seeders;

use App\Models\Bangkom;
use App\Models\Instansi;
use App\Models\JenisPelatihan;
use App\Models\BentukPelatihan;
use App\Models\Sasaran;
use Illuminate\Database\Seeder;

class BangkomSeeder extends Seeder
{
    public function run(): void
    {
        $instansis = Instansi::all();
        $jenisPelatihans = JenisPelatihan::all();
        $bentukPelatihans = BentukPelatihan::all();
        $sasarans = Sasaran::all();

        if ($instansis->isEmpty() || $jenisPelatihans->isEmpty() || $bentukPelatihans->isEmpty() || $sasarans->isEmpty()) {
            $this->command->info('Please run other seeders first (Instansi, JenisPelatihan, BentukPelatihan, Sasaran).');
            return;
        }

        Bangkom::truncate();

        $records = [
            [
                'instansi_id' => $instansis->first()->id,
                'unit_kerja' => 'Dinas Pendidikan',
                'nama_kegiatan' => 'Pelatihan Guru SD',
                'kode_kegiatan' => 'SPL-A001',
                'jenis_pelatihan_id' => $jenisPelatihans->first()->id,
                'bentuk_pelatihan_id' => $bentukPelatihans->first()->id,
                'sasaran_id' => $sasarans->first()->id,
                'tanggal_mulai' => '2025-01-15',
                'tanggal_berakhir' => '2025-01-20',
                'tempat' => 'Aula Sekolah Dasar Negeri 1',
                'alamat' => 'Jl. Pendidikan No. 10, Samarinda',
                'kuota' => 50,
                'nama_panitia' => 'Tim Pendidikan',
                'telepon_panitia' => '081234567890',
                'jadwal_codes' => ['SPL-A101', 'SPL-A102'],
                'status' => 'Published',
            ],
            [
                'instansi_id' => $instansis->first()->id,
                'unit_kerja' => 'Dinas Kesehatan',
                'nama_kegiatan' => 'Workshop Manajemen Rumah Sakit',
                'kode_kegiatan' => 'SPL-A002',
                'jenis_pelatihan_id' => $jenisPelatihans[0]->id ?? $jenisPelatihans->first()->id,
                'bentuk_pelatihan_id' => $bentukPelatihans[1]->id ?? $bentukPelatihans->first()->id,
                'sasaran_id' => $sasarans[0]->id ?? $sasarans->first()->id,
                'tanggal_mulai' => '2025-02-10',
                'tanggal_berakhir' => '2025-02-12',
                'tempat' => 'Rumah Sakit Umum Daerah',
                'alamat' => 'Jl. Kesehatan No. 20, Balikpapan',
                'kuota' => 30,
                'nama_panitia' => 'Panitia Kesehatan',
                'telepon_panitia' => '082345678901',
                'jadwal_codes' => ['SPL-A201', 'SPL-A202'],
                'status' => 'Draft',
            ],
            [
                'instansi_id' => $instansis[1]->id ?? $instansis->first()->id,
                'unit_kerja' => 'Bappeda',
                'nama_kegiatan' => 'Pelatihan Perencanaan Pembangunan',
                'kode_kegiatan' => 'SPL-A003',
                'jenis_pelatihan_id' => $jenisPelatihans[1]->id ?? $jenisPelatihans->first()->id,
                'bentuk_pelatihan_id' => $bentukPelatihans->first()->id,
                'sasaran_id' => $sasarans[1]->id ?? $sasarans->first()->id,
                'tanggal_mulai' => '2025-03-05',
                'tanggal_berakhir' => '2025-03-08',
                'tempat' => 'Gedung Bappeda',
                'alamat' => 'Jl. Pembangunan No. 30, Samarinda',
                'kuota' => 40,
                'nama_panitia' => 'Tim Perencanaan',
                'telepon_panitia' => '083456789012',
                'jadwal_codes' => ['SPL-A301', 'SPL-A302'],
                'status' => 'Published',
            ],
            [
                'instansi_id' => $instansis->first()->id,
                'unit_kerja' => 'Dinas Lingkungan Hidup',
                'nama_kegiatan' => 'Seminar Pengelolaan Sampah',
                'kode_kegiatan' => 'SPL-A004',
                'jenis_pelatihan_id' => $jenisPelatihans->first()->id,
                'bentuk_pelatihan_id' => $bentukPelatihans->first()->id,
                'sasaran_id' => $sasarans->first()->id,
                'tanggal_mulai' => '2025-04-20',
                'tanggal_berakhir' => '2025-04-22',
                'tempat' => 'Balai Lingkungan Hidup',
                'alamat' => 'Jl. Lingkungan No. 40, Kutai Kartanegara',
                'kuota' => 25,
                'nama_panitia' => 'Panitia Lingkungan',
                'telepon_panitia' => '084567890123',
                'jadwal_codes' => ['SPL-A401', 'SPL-A402'],
                'status' => 'Cancelled',
            ],
            [
                'instansi_id' => $instansis[0]->id ?? $instansis->first()->id,
                'unit_kerja' => 'Dinas Pariwisata',
                'nama_kegiatan' => 'Training Guide Wisata',
                'kode_kegiatan' => 'SPL-A005',
                'jenis_pelatihan_id' => $jenisPelatihans[0]->id ?? $jenisPelatihans->first()->id,
                'bentuk_pelatihan_id' => $bentukPelatihans[0]->id ?? $bentukPelatihans->first()->id,
                'sasaran_id' => $sasarans[0]->id ?? $sasarans->first()->id,
                'tanggal_mulai' => '2025-05-15',
                'tanggal_berakhir' => '2025-05-18',
                'tempat' => 'Pusat Pariwisata',
                'alamat' => 'Jl. Wisata No. 50, Samarinda',
                'kuota' => 35,
                'nama_panitia' => 'Tim Pariwisata',
                'telepon_panitia' => '085678901234',
                'jadwal_codes' => ['SPL-A501', 'SPL-A502'],
                'status' => 'Published',
            ],
        ];

        foreach ($records as $record) {
            Bangkom::create($record);
        }

        $this->command->info('BangkomSeeder completed. Added ' . count($records) . ' records.');
    }
}
