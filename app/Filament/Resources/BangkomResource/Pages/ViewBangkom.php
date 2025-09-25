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
                    Forms\Components\TextInput::make('nomor_jadwal')
                        ->label('Nomor Jadwal/Kegiatan')
                        ->disabled(),
                    Forms\Components\Select::make('instansi_id')
                        ->label('Instansi Pelaksana')
                        ->relationship('instansi', 'name')
                        ->disabled(),
                    Forms\Components\TextInput::make('unit_kerja')
                        ->label('Unit Kerja / Perangkat Daerah Pelaksana')
                        ->disabled(),
                    Forms\Components\TextInput::make('nama_kegiatan')
                        ->label('Nama Kegiatan')
                        ->disabled(),
                    Forms\Components\Select::make('jenis_pelatihan_id')
                        ->label('Jenis Pelatihan')
                        ->relationship('jenisPelatihan', 'name')
                        ->disabled(),
                    Forms\Components\Select::make('bentuk_pelatihan_id')
                        ->label('Bentuk Pelatihan')
                        ->relationship('bentukPelatihan', 'name')
                        ->disabled(),
                    Forms\Components\Select::make('sasaran_id')
                        ->label('Sasaran')
                        ->relationship('sasaran', 'name')
                        ->disabled(),
                ])->columns(2),

            Forms\Components\Section::make('Waktu, Tempat dan Kuota')
                ->schema([
                    Forms\Components\DatePicker::make('tanggal_mulai')
                        ->label('Tanggal Mulai')
                        ->disabled(),
                    Forms\Components\DatePicker::make('tanggal_berakhir')
                        ->label('Tanggal Berakhir')
                        ->disabled(),
                    Forms\Components\TextInput::make('tempat')
                        ->label('Tempat')
                        ->disabled(),
                    Forms\Components\Textarea::make('alamat')
                        ->label('Alamat')
                        ->disabled(),
                    Forms\Components\TextInput::make('kuota')
                        ->label('Kuota')
                        ->disabled(),
                ])->columns(2),

            Forms\Components\Section::make('Panitia')
                ->schema([
                    Forms\Components\TextInput::make('nama_panitia')
                        ->label('Nama Panitia')
                        ->disabled(),
                    Forms\Components\TextInput::make('telepon_panitia')
                        ->label('Telepon Panitia')
                        ->disabled(),
                ])->columns(2),

            Forms\Components\Section::make('Kurikulum')
                ->schema([
                    Forms\Components\Repeater::make('kurikulum')
                        ->label('Kurikulum')
                        ->disabled()
                        ->schema([
                            Forms\Components\TextInput::make('narasumber')
                                ->label('Narasumber'),
                            Forms\Components\TextInput::make('materi')
                                ->label('Materi'),
                            Forms\Components\TextInput::make('jam_pelajaran')
                                ->label('Jam Pelajaran'),
                        ]),
                ]),

            Forms\Components\Section::make('Deskripsi Kegiatan & Persyaratan')
                ->schema([
                    Forms\Components\Textarea::make('deskripsi')
                        ->label('Deskripsi')
                        ->disabled(),
                    Forms\Components\Textarea::make('persyaratan')
                        ->label('Persyaratan')
                        ->disabled(),
                ]),
        ];
    }
}
