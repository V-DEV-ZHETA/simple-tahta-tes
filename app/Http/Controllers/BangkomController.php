<?php

namespace App\Http\Controllers;

use App\Models\Bangkom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

/**
 * Controller untuk mengelola operasi terkait Bangkom (Pengembangan Kompetensi).
 * Menangani download dokumen, permohonan, STTP, dan data terkait lainnya.
 */
class BangkomController extends Controller
{
    /**
     * Mengunduh formulir permohonan dalam format DOCX.
     * Membuat dokumen Word lengkap dengan data kegiatan, kurikulum, deskripsi, dan persyaratan.
     *
     * @param Bangkom $bangkom Instance model Bangkom yang akan diunduh.
     * @return void Mengirim file DOCX langsung ke browser.
     */
    public function downloadDocx(Bangkom $bangkom)
    {
        // Memuat relasi yang diperlukan untuk mengakses data terkait
        $bangkom->load(['jenisPelatihan', 'bentukPelatihan', 'sasaran', 'user', 'instansi']);

        // Inisialisasi objek PhpWord untuk membuat dokumen Word
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Menambahkan header dokumen
        $section->addText('FORMULIR PERMOHONAN PENGEMBANGAN KOMPETENSI', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
        $section->addTextBreak(2);

        // Menambahkan data kegiatan utama
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

        // Mengatur header HTTP untuk download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        // Menyimpan dan mengirim dokumen
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
        exit;
    }

    /**
     * Mengunduh file permohonan. Jika ada file yang diupload, unduh file tersebut.
     * Jika tidak, generate dokumen Word lengkap.
     *
     * @param Bangkom $bangkom Instance model Bangkom.
     * @return \Illuminate\Http\Response Response download file.
     */
    public function downloadPermohonan(Bangkom $bangkom)
    {
        // Cek apakah ada file permohonan yang diupload
        if ($bangkom->file_permohonan_path && Storage::exists($bangkom->file_permohonan_path)) {
            return Storage::download($bangkom->file_permohonan_path);
        }

        // Jika tidak ada file, generate dokumen Word lengkap
        return $this->downloadDocx($bangkom);
    }

    /**
     * Mengunduh dokumen STTP (Surat Tugas Pengajar) dalam format DOCX.
     * Saat ini masih basic, bisa dikembangkan lebih lanjut.
     *
     * @param Bangkom $bangkom Instance model Bangkom.
     * @return void Mengirim file DOCX langsung ke browser.
     */
    public function downloadSttp(Bangkom $bangkom)
    {
        // Inisialisasi dokumen Word untuk STTP
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Menambahkan konten dasar STTP
        $section->addText('STTP Bangkom');
        $section->addText('Nama Kegiatan: ' . $bangkom->nama_kegiatan);
        // TODO: Tambahkan field lainnya sesuai kebutuhan, seperti tanggal, peserta, dll.

        // Menentukan nama file
        $fileName = 'STTP_Bangkom_' . $bangkom->id . '.docx';

        // Mengatur header HTTP untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        // Menyimpan dan mengirim dokumen
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
        exit;
    }

    /**
     * Menampilkan informasi kelengkapan dokumen untuk Bangkom tertentu.
     * Saat ini masih placeholder, perlu implementasi lebih lanjut.
     *
     * @param Bangkom $bangkom Instance model Bangkom.
     * @return \Illuminate\Http\JsonResponse Response JSON dengan pesan.
     */
    public function kelengkapanDokumen(Bangkom $bangkom)
    {
        // TODO: Implementasi logika untuk mengecek kelengkapan dokumen
        return response()->json(['message' => 'Kelengkapan Dokumen for Bangkom ' . $bangkom->id]);
    }

    /**
     * Menampilkan dokumentasi untuk Bangkom tertentu.
     * Saat ini masih placeholder, perlu implementasi lebih lanjut.
     *
     * @param Bangkom $bangkom Instance model Bangkom.
     * @return \Illuminate\Http\JsonResponse Response JSON dengan pesan.
     */
    public function dokumentasi(Bangkom $bangkom)
    {
        // TODO: Implementasi logika untuk menampilkan dokumentasi
        return response()->json(['message' => 'Dokumentasi for Bangkom ' . $bangkom->id]);
    }

    /**
     * Menampilkan daftar peserta untuk Bangkom tertentu.
     * Saat ini masih placeholder, perlu implementasi lebih lanjut.
     *
     * @param Bangkom $bangkom Instance model Bangkom.
     * @return \Illuminate\Http\JsonResponse Response JSON dengan pesan.
     */
    public function peserta(Bangkom $bangkom)
    {
        // TODO: Implementasi logika untuk menampilkan daftar peserta
        return response()->json(['message' => 'Peserta for Bangkom ' . $bangkom->id]);
    }
}
