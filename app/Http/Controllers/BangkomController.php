<?php

namespace App\Http\Controllers;

use App\Models\Bangkom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class BangkomController extends Controller
{
    public function downloadDocx(Bangkom $bangkom)
    {
        // Load relasi yang diperlukan
        $bangkom->load(['jenisPelatihan', 'bentukPelatihan', 'sasaran', 'user', 'instansi']);

        $phpWord = new PhpWord();

        $section = $phpWord->addSection();

        // Header
        $section->addText('FORMULIR PERMOHONAN PENGEMBANGAN KOMPETENSI', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
        $section->addTextBreak(2);

        // Data Kegiatan
        $section->addText('Kode Kegiatan: ' . ($bangkom->kode_kegiatan ?? '-'));
        $section->addText('Unit Kerja: ' . ($bangkom->unit_kerja ?? '-'));
        $section->addText('Nama Kegiatan: ' . ($bangkom->nama_kegiatan ?? '-'));
        $section->addText('Jenis Pelatihan: ' . ($bangkom->jenisPelatihan->name ?? '-'));
        $section->addText('Bentuk Pelatihan: ' . ($bangkom->bentukPelatihan->bentuk ?? '-'));
        $section->addText('Sasaran: ' . ($bangkom->sasaran->name ?? '-'));
        $section->addText('Periode: ' . ($bangkom->tanggal_mulai ? $bangkom->tanggal_mulai->format('d/m/Y') : '-') . ' - ' . ($bangkom->tanggal_selesai ? $bangkom->tanggal_selesai->format('d/m/Y') : '-'));
        $section->addText('Tempat: ' . ($bangkom->tempat ?? '-'));
        $section->addText('Alamat: ' . ($bangkom->alamat ?? '-'));
        $section->addText('Kuota: ' . ($bangkom->kuota ?? '-') . ' orang');
        $section->addText('Nama Panitia: ' . ($bangkom->nama_panitia ?? '-'));
        $section->addText('No. Telepon: ' . ($bangkom->no_telp ?? '-'));
        $section->addTextBreak(1);

        // Kurikulum
        if ($bangkom->kurikulum && count($bangkom->kurikulum) > 0) {
            $section->addText('KURIKULUM', ['bold' => true]);
            $table = $section->addTable();
            $table->addRow();
            $table->addCell(2000)->addText('Narasumber');
            $table->addCell(4000)->addText('Materi');
            $table->addCell(2000)->addText('Jam Pelajaran');

            foreach ($bangkom->kurikulum as $item) {
                $table->addRow();
                $table->addCell(2000)->addText($item['Narasumber'] ?? '-');
                $table->addCell(4000)->addText($item['materi'] ?? '-');
                $table->addCell(2000)->addText($item['jam_pelajaran'] ?? '-');
            }
            $section->addTextBreak(1);
        }

        // Deskripsi
        if ($bangkom->deskripsi) {
            $section->addText('DESKRIPSI', ['bold' => true]);
            $section->addText($bangkom->deskripsi);
            $section->addTextBreak(1);
        }

        // Persyaratan
        if ($bangkom->persyaratan) {
            $section->addText('PERSYARATAN', ['bold' => true]);
            $section->addText($bangkom->persyaratan);
            $section->addTextBreak(1);
        }

        // Tanda tangan
        $section->addTextBreak(3);
        $section->addText(($bangkom->instansi->name ?? 'Instansi Tidak Ditemukan') . ', ' . now()->format('d F Y'), [], ['alignment' => 'right']);
        $section->addTextBreak(3);
        $section->addText(($bangkom->user->name ?? 'User Tidak Ditemukan'), [], ['alignment' => 'right']);

        $fileName = 'Permohonan_Bangkom_' . ($bangkom->kode_kegiatan ?? $bangkom->id) . '.docx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
        exit;
    }

    public function downloadPermohonan(Bangkom $bangkom)
    {
        // Jika ada file yang diupload, download file tersebut
        if ($bangkom->file_permohonan_path && Storage::exists($bangkom->file_permohonan_path)) {
            return Storage::download($bangkom->file_permohonan_path);
        }

        // Jika tidak ada file, generate dokumen Word lengkap
        return $this->downloadDocx($bangkom);
    }

    public function downloadSttp(Bangkom $bangkom)
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $section->addText('STTP Bangkom');
        $section->addText('Nama Kegiatan: ' . $bangkom->nama_kegiatan);
        // Add more fields as needed

        $fileName = 'STTP_Bangkom_' . $bangkom->id . '.docx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
        exit;
    }

    public function kelengkapanDokumen(Bangkom $bangkom)
    {
        // Placeholder implementation
        return response()->json(['message' => 'Kelengkapan Dokumen for Bangkom ' . $bangkom->id]);
    }

    public function dokumentasi(Bangkom $bangkom)
    {
        // Placeholder implementation
        return response()->json(['message' => 'Dokumentasi for Bangkom ' . $bangkom->id]);
    }

    public function peserta(Bangkom $bangkom)
    {
        // Placeholder implementation
        return response()->json(['message' => 'Peserta for Bangkom ' . $bangkom->id]);
    }
}
