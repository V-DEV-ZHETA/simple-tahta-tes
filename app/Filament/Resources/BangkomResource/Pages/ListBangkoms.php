<?php

namespace App\Filament\Resources\BangkomResource\Pages;

use App\Filament\Resources\BangkomResource;
use App\Exports\BangkomExport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Bangkom;

class ListBangkoms extends ListRecords
{
    protected static string $resource = BangkomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('totalRecords')
                ->label(fn () => 'Total Records ' . Bangkom::count())
                ->color('warning')
                ->icon('heroicon-o-information-circle')
                ->disabled(),
            Actions\Action::make('exportJadwal')
                ->label('Export Jadwal Bangkom')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    return Excel::download(new BangkomExport(), 'jadwal-bangkom-' . now()->format('Y-m-d') . '.xlsx');
                }),
            Actions\CreateAction::make()
                ->label('Buat Jadwal'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            //
        ];
    }
}
