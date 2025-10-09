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

    /**
     * @return array<class-string<RelationManager> | RelationGroup | RelationManagerConfiguration>
     */
    // public function getRelationManagers(): array
    // {
    //     return [
    //         PesertaRelationManager::class,
    //     ];
    // }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kegiatan')
                    ->schema([
                        TextInput::make('nomor_jadwal')
                            ->label('Nomor Jadwal/Kegiatan')
                            ->disabled()
                            ->dehydrated(),

                        Select::make('pengelola')
                            ->label('Pengelola')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('instansi_pelaksana')
                            ->label('Instansi Pelaksana')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('unit_kerja')
                            ->label('Unit Kerja / Perangkat Daerah Pelaksana')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->disabled()
                            ->dehydrated(),

                        Select::make('jenis_pelatihan')
                            ->label('Jenis Pelatihan')
                            ->disabled()
                            ->dehydrated(),

                        Select::make('bentuk_pelatihan')
                            ->label('Bentuk Pelatihan')
                            ->disabled()
                            ->dehydrated(),

                        Select::make('sasaran')
                            ->label('Sasaran')
                            ->disabled()
                            ->dehydrated(),
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

    /**
     * @return array<Action | ActionGroup>
     */

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
