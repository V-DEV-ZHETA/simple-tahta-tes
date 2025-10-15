<?php

namespace App\Services;

use App\Models\Bangkom;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class BangkomPrintService
{
    public function cetakPermohonan(Bangkom $bangkom): string
    {
        // Generate PDF content for permohonan
        $pdf = Pdf::loadView('pdf.permohonan', compact('bangkom'));
        $fileName = 'permohonan_' . $bangkom->id . '.pdf';
        $path = 'temp/' . $fileName;
        Storage::put($path, $pdf->output());
        return Storage::path($path);
    }
}
