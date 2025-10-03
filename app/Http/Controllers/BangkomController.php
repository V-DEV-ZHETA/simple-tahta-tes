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
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $section->addText('Cetak Permohonan Bangkom');
        $section->addText('Nama Kegiatan: ' . $bangkom->nama_kegiatan);
        $section->addText('Jenis Pelatihan: ' . $bangkom->jenisPelatihan->name);
        $section->addText('Tanggal Mulai: ' . $bangkom->tanggal_mulai);
        $section->addText('Tanggal Berakhir: ' . $bangkom->tanggal_berakhir);
        $section->addText('Kuota: ' . $bangkom->kuota);
        // Add more fields as needed

        $fileName = 'Cetak_Permohonan_Bangkom_' . $bangkom->id . '.docx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
        exit;
    }

    public function downloadPermohonan(Bangkom $bangkom)
    {
        // Assuming the file is stored in storage/app/public/permohonan_files or similar
        // You may need to adjust the path based on your storage configuration
        if ($bangkom->file_permohonan_path && Storage::exists($bangkom->file_permohonan_path)) {
            return Storage::download($bangkom->file_permohonan_path);
        }

        // If no file is uploaded, generate a default document or show error
        return response()->json(['error' => 'File permohonan tidak ditemukan'], 404);
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
