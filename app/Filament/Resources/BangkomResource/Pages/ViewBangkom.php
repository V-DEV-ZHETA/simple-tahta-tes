<?php

namespace App\Filament\Resources\BangkomResource\Pages;

use App\Enums\BangkomStatus;
use App\Filament\Resources\BangkomResource;
use App\Filament\Resources\BangkomResource\RelationManagers\PesertaRelationManager;
use App\Models\Bangkom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Pages\Page;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewBangkom extends ViewRecord
{
    protected static string $resource = BangkomResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kegiatan')
                    ->schema([
                        TextInput::make('kode_kegiatan')
                            ->label('Nomor Jadwal/Kegiatan')
                            ->disabled()
                            ->default(fn ($record) => $record->kode_kegiatan ?? '-'),
                        Select::make('instansi_id')
                            ->label('Instansi Pelaksana')
                            ->options(\App\Models\Instansi::all()->pluck('name', 'id')->toArray())
                            ->disabled()
                            ->default(fn ($record) => $record->instansi_id),

                        TextInput::make('unit_kerja')
                            ->label('Unit Kerja / Perangkat Daerah Pelaksana')
                            ->disabled()
                            ->default(fn ($record) => $record->unit_kerja ?? '-'),

                        TextInput::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->disabled()
                            ->default(fn ($record) => $record->nama_kegiatan ?? '-'),

                        Select::make('jenis_pelatihan_id')
                            ->label('Jenis Pelatihan')
                            ->options(\App\Models\JenisPelatihan::all()->pluck('name', 'id')->toArray())
                            ->disabled()
                            ->default(fn ($record) => $record->jenis_pelatihan_id),

                        Select::make('bentuk_pelatihan_id')
                            ->label('Bentuk Pelatihan')
                            ->options(\App\Models\BentukPelatihan::all()->pluck('bentuk', 'id')->toArray())
                            ->disabled()
                            ->default(fn ($record) => $record->bentuk_pelatihan_id),

                        Select::make('sasaran_id')
                            ->label('Sasaran')
                            ->options(\App\Models\Sasaran::all()->pluck('name', 'id')->toArray())
                            ->disabled()
                            ->default(fn ($record) => $record->sasaran_id),

                    ])
                    ->collapsible()->InLineLabel(),

                Section::make('Waktu, Tempat dan Kuota')
                    ->schema([
                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->disabled()
                            ->dehydrated(),

                        DatePicker::make('tanggal_berakhir')
                            ->label('Tanggal Berakhir')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('tempat')
                            ->label('Tempat')
                            ->placeholder('Venue/Tempat Kegiatan')
                            ->disabled()
                            ->dehydrated(),

                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->disabled()
                            ->dehydrated()
                            ->rows(3),

                        TextInput::make('kuota')
                            ->label('Kuota')
                            ->disabled()
                            ->dehydrated()
                            ->numeric()
                            ->suffix('peserta'),
                    ])
                    ->collapsible()->InLineLabel(),

                Section::make('Panitia')
                    ->schema([
                        TextInput::make('nama_panitia')
                            ->label('Nama Panitia')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('telepon_panitia')
                            ->label('Telepon Panitia')
                            ->disabled()
                            ->dehydrated()
                            ->tel(),
                    ])
                    ->collapsible()->InLineLabel(),

                Section::make('Kurikulum')
                    ->schema([
                        Repeater::make('kurikulum')
                            ->schema([
                                TextInput::make('no')
                                    ->label('Narasumber')
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('materi')
                                    ->label('Materi')
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('jam_pelajaran')
                                    ->label('Jam Pelajaran')
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns(3)
                            ->disabled()
                            ->dehydrated()
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->defaultItems(0),
                    ])
                    ->collapsible(),

                Section::make('Deskripsi Kegiatan & Persyaratan')
                    ->schema([
                        Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->disabled()
                            ->dehydrated()
                            ->rows(2)
                            ->columnSpanFull(),

                        Textarea::make('persyaratan')
                            ->label('Persyaratan')
                            ->disabled()
                            ->dehydrated()
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()->InLineLabel(),
            ])
            ->columns(1);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Dokumentasi berhasil disimpan')
            ->body('Dokumentasi kegiatan telah berhasil diupload.');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->getRecord()->status === BangkomStatus::Pengelolaan) {
            return [
                'dokumentasi' => $data['dokumentasi'] ?? [],
            ];
        }

        return [];
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
