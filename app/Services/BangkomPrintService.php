<?php

namespace App\Services;

use App\Models\Bangkom;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class BangkomPrintService
{
    public function cetakPermohonan(Bangkom $bangkom): string
    {
        // Generate DOCX content for permohonan
        $bangkom->load(['jenisPelatihan', 'bentukPelatihan', 'sasaran', 'user', 'instansi']);

        // Inisialisasi objek PhpWord untuk membuat dokumen Word
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Menambahkan header dokumen
        $section->addText('FORMULIR PERMOHONAN PENGEMBANGAN KOMPETENSI', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
        $section->addTextBreak(2);

        // Menambahkan data kegiatan utama dalam format tabel
        $table = $section->addTable();
        $table->addRow();
        $table->addCell(2000)->addText('Kode Kegiatan');
        $table->addCell(4000)->addText(': ' . ($bangkom->kode_kegiatan ?? '-'));
        $table->addRow();
        $table->addCell(2000)->addText('Unit Kerja');
        $table->addCell(4000)->addText(': ' . ($bangkom->unit_kerja ?? '-'));
        $table->addRow();
        $table->addCell(2000)->addText('Nama Kegiatan');
        $table->addCell(4000)->addText(': ' . ($bangkom->nama_kegiatan ?? '-'));
        $table->addRow();
        $table->addCell(2000)->addText('Jenis Pelatihan');
        $table->addCell(4000)->addText(': ' . ($bangkom->jenisPelatihan->nama ?? '-'));
        $table->addRow();
        $table->addCell(2000)->addText('Bentuk Pelatihan');
        $table->addCell(4000)->addText(': ' . ($bangkom->bentukPelatihan->nama ?? '-'));
        $table->addRow();
        $table->addCell(2000)->addText('Sasaran');
        $table->addCell(4000)->addText(': ' . ($bangkom->sasaran->nama ?? '-'));
        $table->addRow();
        $table->addCell(2000)->addText('Periode');
        $table->addCell(4000)->addText(': ' . ($bangkom->tanggal_mulai ? $bangkom->tanggal_mulai->format('d/m/Y') : '-') . ' - ' . ($bangkom->tanggal_selesai ? $bangkom->tanggal_selesai->format('d/m/Y') : '-'));
        $table->addRow();
        $table->addCell(2000)->addText('Tempat');
        $table->addCell(4000)->addText(': ' . ($bangkom->tempat ?? '-'));
        $table->addRow();
        $table->addCell(2000)->addText('Alamat');
        $table->addCell(4000)->addText(': ' . ($bangkom->alamat ?? '-'));
        $table->addRow();
        $table->addCell(2000)->addText('Kuota');
        $table->addCell(4000)->addText(': ' . ($bangkom->kuota ?? '-') . ' orang');
        $table->addRow();
        $table->addCell(2000)->addText('Nama Panitia');
        $table->addCell(4000)->addText(': ' . ($bangkom->nama_panitia ?? '-'));
        $table->addRow();
        $table->addCell(2000)->addText('No. Telepon');
        $table->addCell(4000)->addText(': ' . ($bangkom->no_telp ?? '-'));
        $section->addTextBreak(1);

        // Menambahkan bagian kurikulum jika ada
        if ($bangkom->kurikulum && count($bangkom->kurikulum) > 0) {
            $section->addText('KURIKULUM', ['bold' => true]);

            // Membuat tabel untuk kurikulum
            $table = $section->addTable();
            $table->addRow();
            $table->addCell(2000)->addText('Narasumber');
            $table->addCell(4000)->addText('Materi');
            $table->addCell(2000)->addText('Jam Pelajaran');

            // Mengisi tabel dengan data kurikulum
            foreach ($bangkom->kurikulum as $item) {
                $table->addRow();
                $table->addCell(2000)->addText($item['Narasumber'] ?? '-');
                $table->addCell(4000)->addText($item['materi'] ?? '-');
                $table->addCell(2000)->addText($item['jam_pelajaran'] ?? '-');
            }
            $section->addTextBreak(1);
        }

        // Menambahkan deskripsi jika ada
        if ($bangkom->deskripsi) {
            $section->addText('DESKRIPSI', ['bold' => true]);
            $section->addText($bangkom->deskripsi);
            $section->addTextBreak(1);
        }

        // Menambahkan persyaratan jika ada
        if ($bangkom->persyaratan) {
            $section->addText('PERSYARATAN', ['bold' => true]);
            $section->addText($bangkom->persyaratan);
            $section->addTextBreak(1);
        }

        // Menambahkan bagian tanda tangan
        $section->addTextBreak(3);
        $section->addText(($bangkom->instansi->name ?? 'Instansi Tidak Ditemukan') . ', ' . now()->format('d F Y'), [], ['alignment' => 'right']);
        $section->addTextBreak(3);
        $section->addText(($bangkom->user->name ?? 'User Tidak Ditemukan'), [], ['alignment' => 'right']);

        // Menentukan nama file
        $fileName = 'Permohonan_Bangkom_' . ($bangkom->kode_kegiatan ?? $bangkom->id) . '.docx';

        // Menyimpan dokumen ke storage sementara
        $path = 'temp/' . $fileName;
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $tempPath = Storage::path($path);
        $objWriter->save($tempPath);

        return $tempPath;
    }
}
