<?php

namespace App\Filament\Resources\BangkomResource\Pages;

use App\Enums\BangkomStatus;
use App\Filament\Resources\BangkomResource;
use App\Models\Bangkom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers;

class ManagePeserta extends EditRecord
{
    protected static string $resource = BangkomResource::class;

    protected static ?string $title = 'Kelola Peserta';

    /**
     * @return array<class-string<RelationManager> | RelationGroup | RelationManagerConfiguration>
     */
    public function getRelationManagers(): array
    {
        return [
            RelationManagers\PesertaRelationManager::class,
        ];
    }

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->operation('edit')
                    ->model($this->getRecord())
                    ->statePath($this->getFormStatePath())
                    ->columns($this->hasInlineLabels() ? 1 : 2)
                    ->schema([
                        Repeater::make('peserta')
                            ->label('Daftar Peserta')
                            ->schema([
                                TextInput::make('nama')
                                    ->label('Nama Peserta')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('nip')
                                    ->label('NIP')
                                    ->maxLength(255),
                                TextInput::make('jabatan')
                                    ->label('Jabatan')
                                    ->maxLength(255),
                                TextInput::make('instansi')
                                    ->label('Instansi')
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->addActionLabel('Tambah Peserta')
                            ->defaultItems(0)
                            ->columnSpanFull()
                            ->helperText('Masukkan data peserta yang mengikuti kegiatan'),
                    ])
                    ->inlineLabel($this->hasInlineLabels())
            ),
        ];
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        // Tampilkan tombol jika status adalah Pengelolaan, Menunggu Verifikasi II, atau Terbit STTP
        $allowedStatuses = [
            BangkomStatus::Pengelolaan,
            BangkomStatus::MenungguVerifikasiII,
            BangkomStatus::TerbitSTTP,
        ];

        if (!in_array($this->getRecord()->status, $allowedStatuses)) {
            return [];
        }

        return [
            $this->getSaveFormAction()
                ->label('Simpan Peserta')
                ->successNotificationTitle('Data peserta berhasil disimpan'),
            $this->getCancelFormAction()
                ->label('Kembali'),
        ];
    }

    /**
     * Hook setelah save
     */
    protected function afterSave(): void
    {
        Notification::make()
            ->success()
            ->title('Data Peserta Berhasil Disimpan')
            ->body('Data peserta kegiatan telah berhasil disimpan.')
            ->send();
    }

    /**
     * Redirect setelah save
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Header actions
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali')
                ->color('gray')
                ->url($this->getResource()::getUrl('index')),
        ];
    }
}
