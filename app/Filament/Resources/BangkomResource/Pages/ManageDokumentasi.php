<?php

namespace App\Filament\Resources\BangkomResource\Pages;

use App\Enums\BangkomStatus;
use App\Filament\Resources\BangkomResource;
use App\Models\Bangkom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManageDokumentasi extends EditRecord
{
    protected static string $resource = BangkomResource::class;

    // JANGAN tambahkan property $view di sini
    // protected static string $view = '...'; // HAPUS INI jika ada

    protected static ?string $title = 'Kelola Dokumentasi';

    /**
     * @return array<class-string<RelationManager> | RelationGroup | RelationManagerConfiguration>
     */
    public function getRelationManagers(): array
    {
        return [
            // don't show relation manager on edit
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
                        FileUpload::make('dokumentasi')
                            ->label('Dokumentasi')
                            ->directory('dokumentasi')
                            ->panelLayout('grid')
                            ->multiple()
                            ->image()
                            ->maxSize(5120) // 5MB
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->downloadable()
                            ->openable()
                            ->previewable()
                            ->reorderable()
                            ->columnSpanFull()
                            ->helperText('Upload foto dokumentasi kegiatan (Max: 5MB per file)'),
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
        // Hanya tampilkan tombol jika status adalah Pengelolaan
        if ($this->getRecord()->status !== BangkomStatus::Pengelolaan) {
            return [];
        }

        return [
            $this->getSaveFormAction()
                ->label('Simpan Dokumentasi')
                ->successNotificationTitle('Dokumentasi berhasil disimpan'),
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
            ->title('Dokumentasi Berhasil Disimpan')
            ->body('Dokumentasi kegiatan telah berhasil diupload.')
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