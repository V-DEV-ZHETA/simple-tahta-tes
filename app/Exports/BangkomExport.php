<?php

namespace App\Exports;

use App\Models\Bangkom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class BangkomExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function collection()
    {
        return Bangkom::with(['jenisPelatihan'])->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kegiatan',
            'Jenis Pelatihan',
            'Tanggal Mulai',
            'Tanggal Berakhir',
            'Kuota',
            'Status',
        ];
    }

    public function map($bangkom): array
    {
        return [
            $bangkom->id,
            $bangkom->nama_kegiatan,
            $bangkom->jenisPelatihan?->name ?? '',
            Carbon::parse($bangkom->tanggal_mulai)->format('d M Y'),
            Carbon::parse($bangkom->tanggal_berakhir)->format('d M Y'),
            $bangkom->kuota,
            $bangkom->status,
        ];
    }

    public function title(): string
    {
        return 'Jadwal Bangkom';
    }
}
