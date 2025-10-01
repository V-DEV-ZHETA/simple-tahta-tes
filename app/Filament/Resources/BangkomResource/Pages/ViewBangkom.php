<?php

namespace App\Filament\Resources\BangkomResource\Pages;

use App\Filament\Resources\BangkomResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;

class ViewBangkom extends ViewRecord
{
    protected static string $resource = BangkomResource::class;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Kegiatan')
                ->schema([
                    Forms\Components\Placeholder::make('kode_kegiatan')
                        ->label('Nomor Jadwal/Kegiatan')
                        ->content(fn ($record) => $record->kode_kegiatan ?? '-')
                        ->columnSpanFull(),
                    Forms\Components\Placeholder::make('instansi.name')
                        ->label('Instansi Pelaksana')
                        ->content(fn ($record) => $record->instansi->name ?? '-')
                        ->columnSpanFull(),
                    Forms\Components\Placeholder::make('unit_kerja')
                        ->label('Unit Kerja / Perangkat Daerah Pelaksana')
                        ->content(fn ($record) => $record->unit_kerja ?? '-')
                        ->columnSpanFull(),
                    Forms\Components\Placeholder::make('nama_kegiatan')
                        ->label('Nama Kegiatan')
                        ->content(fn ($record) => $record->nama_kegiatan ?? '-')
                        ->columnSpanFull(),
                    Forms\Components\Placeholder::make('jenisPelatihan.name')
                        ->label('Jenis Pelatihan')
                        ->content(fn ($record) => $record->jenisPelatihan->name ?? '-')
                        ->columnSpanFull(),
                    Forms\Components\Placeholder::make('bentukPelatihan.bentuk')
                        ->label('Bentuk Pelatihan')
                        ->content(fn ($record) => $record->bentukPelatihan->bentuk ?? '-')
                        ->columnSpanFull(),
                    Forms\Components\Placeholder::make('sasaran.name')
                        ->label('Sasaran')
                        ->content(fn ($record) => $record->sasaran->name ?? '-')
                        ->columnSpanFull(),
                ])
                ->columns(1)
                ->compact(),

            Forms\Components\Section::make('Waktu, Tempat dan Kuota')
                ->schema([
                    Forms\Components\Placeholder::make('tanggal_mulai')
                        ->label('Tanggal Mulai')
                        ->content(fn ($record) => $record->tanggal_mulai ? $record->tanggal_mulai->format('d/m/Y') : '-')
                        ->columnSpanFull(),
                    Forms\Components\Placeholder::make('tanggal_berakhir')
                        ->label('Tanggal Berakhir')
                        ->content(fn ($record) => $record->tanggal_berakhir ? $record->tanggal_berakhir->format('d/m/Y') : '-')
                        ->columnSpanFull(),
                    Forms\Components\Placeholder::make('tempat')
                        ->label('Tempat')
                        ->content(fn ($record) => $record->tempat ?? '-')
                        ->columnSpanFull(),
                    Forms\Components\Placeholder::make('alamat')
                        ->label('Alamat')
                        ->content(fn ($record) => $record->alamat ?? '-')
                        ->columnSpanFull(),
                    Forms\Components\Placeholder::make('kuota')
                        ->label('Kuota')
                        ->content(fn ($record) => $record->kuota ?? '-')
                        ->columnSpanFull(),
                ])
                ->columns(1)
                ->compact(),
        ];
    }
}