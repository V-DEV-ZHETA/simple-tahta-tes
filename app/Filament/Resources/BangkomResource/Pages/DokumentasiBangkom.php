<?php

namespace App\Filament\Resources\BangkomResource\Pages;

use App\Filament\Resources\BangkomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;

use App\Models\Bangkom;

class DokumentasiBangkom extends EditRecord
{
    protected static string $resource = BangkomResource::class;

    protected static ?string $model = Bangkom::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali')
                ->url(fn () => static::getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dokumentasi')
                    ->schema([
                        Textarea::make('deskripsi_dokumentasi')
                            ->label('Dokumentasi')
                            ->rows(5)
                            ->placeholder('Masukkan deskripsi atau catatan dokumentasi kegiatan...')
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
                Section::make('Lampiran')
                    ->schema([
                        Repeater::make('dokumentasi')
                            ->label('Lampiran')
                            ->schema([
                                FileUpload::make('file_path')
                                    ->label('File')
                                    ->disk('public')
                                    ->directory('dokumentasi')
                                    ->maxSize(10240)
                                    ->acceptedFileExtensions(['pdf', 'docx', 'xlsx', 'pptx', 'jpg', 'jpeg', 'png', 'gif'])
                                    ->enableOpen()
                                    ->enableDownload()
                                    ->helperText('Ukuran maksimal 10MB. Format: PDF, DOCX, XLSX, PPTX, JPEG, PNG, GIF.'),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('Tambah')
                            ->deleteActionLabel('Hapus')
                            ->reorderable(false)
                            ->columns(1),
                    ])
                    ->columns(1),
            ])
            ->columns(1)
            ->maxWidth('md');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['dokumentasi'] = collect($data['dokumentasi'] ?? [])->map(fn ($path) => ['file_path' => $path])->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['dokumentasi'] = collect($data['dokumentasi'] ?? [])->pluck('file_path')->filter()->values()->toArray();

        return $data;
    }

    public function getTitle(): string
    {
        return 'Edit Dokumentasi Jadwal';
    }
}
