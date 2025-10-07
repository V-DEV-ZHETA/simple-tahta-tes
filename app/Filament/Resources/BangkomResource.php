<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BangkomResource\Pages;
use App\Models\Bangkom;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Storage;

class BangkomResource extends Resource
{
    protected static ?string $model = Bangkom::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static function getFormSchema(): array
    {
        return [
            Wizard::make([
                Step::make('Kegiatan')
                    ->columns(12)
                    ->schema([
                        Forms\Components\Select::make('instansi_id')
                            ->label('Instansi Pelaksana')
                            ->relationship('instansi', 'name')
                            ->required()
                            ->columnSpan(12),
                        Forms\Components\TextInput::make('unit_kerja')
                            ->label('Unit Kerja / Perangkat Daerah Pelaksana')
                            ->required()
                            ->columnSpan(12),
                        Forms\Components\TextInput::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->required()
                            ->columnSpan(12),
                        Forms\Components\Select::make('jenis_pelatihan_id')
                            ->label('Jenis Pelatihan')
                            ->relationship('jenisPelatihan', 'name')
                            ->required()
                            ->columnSpan(12),
                        Forms\Components\Select::make('bentuk_pelatihan_id')
                            ->label('Bentuk Pelatihan')
                            ->relationship('bentukPelatihan', 'bentuk')
                            ->required()
                            ->columnSpan(12),
                        Forms\Components\Select::make('sasaran_id')
                            ->label('Sasaran')
                            ->relationship('sasaran', 'name')
                            ->required()
                            ->columnSpan(12),
                    ]),
                Step::make('Waktu, Tempat dan Kuota')
                    ->columns(12)
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->columnSpan(12),
                        Forms\Components\DatePicker::make('tanggal_berakhir')
                            ->label('Tanggal Berakhir')
                            ->required()
                            ->columnSpan(12),
                        Forms\Components\TextInput::make('tempat')
                            ->label('Tempat')
                            ->placeholder('Venue/Tempat Kegiatan')
                            ->required()
                            ->columnSpan(12),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->columnSpan(12),
                        Forms\Components\TextInput::make('kuota')
                            ->label('Kuota')
                            ->required()
                            ->rules(['integer', 'min:1'])
                            ->dehydrateStateUsing(fn ($state) => is_numeric($state) ? (int) $state : null)
                            ->columnSpan(2),

                    ]),
                Step::make('Panitia')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama_panitia')
                            ->label('Nama Panitia')
                            ->required()
                            ->columnSpan(12),
                        Forms\Components\TextInput::make('telepon_panitia')
                            ->label('Telepon Panitia')
                            ->required()
                            ->columnSpan(12),
                    ]),
                Step::make('Kurikulum')
                    ->schema([
                        Forms\Components\Repeater::make('kurikulum')
                            ->label('Kurikulum')
                            ->schema([
                                Forms\Components\TextInput::make('narasumber')
                                    ->label('Narasumber')
                                    ->required()
                                    ->columnSpan(4),
                                Forms\Components\TextInput::make('materi')
                                    ->label('Materi')
                                    ->required()
                                    ->columnSpan(4),
                                Forms\Components\TextInput::make('jam_pelajaran')
                                    ->label('Jam Pelajaran')
                                    ->required()
                                    ->columnSpan(4),
                            ])
                            ->columns(12)
                            ->columnSpan(12),
                    ]),
                Step::make('Deskripsi Kegiatan & Persyaratan')
                    ->schema([
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->required()
                            ->columnSpan(12),
                        Forms\Components\Textarea::make('persyaratan')
                            ->label('Persyaratan')
                            ->required()
                            ->columnSpan(12),
                    ]),
            ])->columnSpanFull()
              ->startOnStep(4)
              ->extraAttributes(['style' => 'width: 100%; max-width: none;']),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema(static::getFormSchema());
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('No')
                    ->sortable()
                    ->toggleable(false)
                    ->getStateUsing(fn ($record, $rowLoop) => $rowLoop->iteration),
Tables\Columns\TextColumn::make('nama_kegiatan')
    ->label('Nama Kegiatan')
    ->sortable()
    ->searchable()
    ->html()
->formatStateUsing(function ($state, $record) {
    $name = e($state);
    $code = $record->kode_kegiatan ?? '';
    $badge = $code ? '<div style="margin-top: 4px; display: block; background-color: #fff1e7ff; color: #ff6a00ff; font-weight: 600; font-size: 0.65rem; padding: 1px 10px; border-radius: 6px; text-transform: uppercase; width: fit-content;">' . e($code) . '</div>' : '';
    return $name . $badge;
}),
Tables\Columns\TextColumn::make('jenisPelatihan.name')
    ->label('Jenis Pelatihan')
    ->sortable()
    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Pelatihan')
                    ->formatStateUsing(function ($state, $record) {
                        $start = Carbon::parse($record->tanggal_mulai)->format('d M');
                        $end = Carbon::parse($record->tanggal_berakhir)->format('d M');
                        return $start . ' s/d ' . $end;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('kuota')
                    ->label('Kuota')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => 'Draft',
                        'success' => 'Verifikasi Berhasil',
                        'danger' => 'Cancelled',
                        'warning' => 'Menunggu Verifikasi',
                    ])
                    ->sortable(),
                    
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Draft' => 'Draft',
                        'Published' => 'Published',
                        'Cancelled' => 'Cancelled',
                        'Submitted' => 'Submitted',
                    ]),
                Tables\Filters\SelectFilter::make('jenis_pelatihan_id')
                    ->relationship('jenisPelatihan', 'name')
                    ->label('Jenis Pelatihan'),
                Tables\Filters\SelectFilter::make('bentuk_pelatihan_id')
                    ->relationship('bentukPelatihan', 'bentuk')
                    ->label('Bentuk Pelatihan'),
                Tables\Filters\SelectFilter::make('sasaran_id')
                    ->relationship('sasaran', 'name')
                    ->label('Sasaran'),
            ])
->actions([
        Tables\Actions\ActionGroup::make([
Tables\Actions\Action::make('cetak_permohonan')
    ->label('Cetak permohonan')
    ->icon('heroicon-o-printer')
    ->url(fn ($record) => route('bangkom.downloadDocx', $record))
    ->visible(fn ($record) => in_array($record->status, ['Draft', 'Menunggu Verifikasi I'])),
Tables\Actions\Action::make('dokumen_permohonan')
    ->label('Dokumen Permohonan')
    ->icon('heroicon-o-document-text')
    ->form([
        Forms\Components\FileUpload::make('file_permohonan')
            ->label('File Permohonan')
            ->required()
            ->maxSize(102400) // 100MB in KB
            ->acceptedFileTypes(['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'image/jpeg'])
            ->helperText('Ukuran file maksimum: 100MB. Format yang diizinkan: PDF, DOCX, XLSX, PPTX, JPEG.')
            ->enableOpen()
            ->enableDownload(),
    ])
    ->modalHeading('Dokumen Permohonan')
    ->modalSubmitActionLabel('Simpan')
    ->modalWidth('md')
    ->action(function ($record, array $data) {
        if (isset($data['file_permohonan']) && is_object($data['file_permohonan'])) {
            $filePath = $data['file_permohonan']->store('permohonan_files');
            $record->file_permohonan_path = $filePath;
            $record->save();
        }
        Notification::make()
            ->title('File permohonan berhasil disimpan')
            ->success()
            ->send();
    })
    ->modalActions([
        Tables\Actions\Action::make('close')
            ->label('Tutup')
            ->close(),
    ])
    ->visible(fn ($record) => in_array($record->status, ['Draft', 'Menunggu Verifikasi I', 'Pengelolaan', 'Menunggu Verifikasi II', 'Terbit STTP'])),
Tables\Actions\Action::make('kelengkapan_dokumen')
    ->label('Kelengkapan Dokumen')
    ->icon('heroicon-o-user')
    ->form([
        Forms\Components\FileUpload::make('bahan_tayang')
            ->label('Bahan Tayang')
            ->maxSize(102400) // 100MB
            ->acceptedFileTypes(['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'image/jpeg'])
            ->helperText('Ukuran file maksimum: 100MB. Format yang diizinkan: PDF, DOCX, XLSX, PPTX, JPEG.'),
        Forms\Components\FileUpload::make('pelaporan')
            ->label('Pelaporan')
            ->maxSize(10240) // 10MB
            ->acceptedFileTypes(['application/pdf'])
            ->required()
            ->helperText('Ukuran file maksimum: 10MB. Format yang diizinkan: PDF.'),
        Forms\Components\FileUpload::make('absensi')
            ->label('Absensi')
            ->maxSize(10240) // 10MB
            ->acceptedFileTypes(['application/pdf'])
            ->required()
            ->helperText('Ukuran file maksimum: 10MB. Format yang diizinkan: PDF.'),
        Forms\Components\FileUpload::make('surat_ijin')
            ->label('Surat Ijin Penggunaan Spesimen TTD Kepala')
            ->maxSize(10240) // 10MB
            ->acceptedFileTypes(['application/pdf'])
            ->required()
            ->helperText('Ukuran file maksimum: 10MB. Format yang diizinkan: PDF.'),
        Forms\Components\FileUpload::make('contoh_spesimen')
            ->label('Contoh Spesimen TTD Kepala')
            ->maxSize(10240) // 10MB
            ->acceptedFileTypes(['image/png'])
            ->required()
            ->helperText('Ukuran file maksimum: 10MB. Format: PNG. Resolusi wajib HD.'),
    ])
    ->modalHeading('Kelengkapan Dokumen')
    ->modalSubmitActionLabel('Simpan')
    ->action(function ($record, array $data) {
        // Save uploaded files and update the model
        if (isset($data['bahan_tayang']) && is_object($data['bahan_tayang'])) {
            $record->bahan_tayang_path = $data['bahan_tayang']->store('kelengkapan_files');
        }
        if (isset($data['pelaporan']) && is_object($data['pelaporan'])) {
            $record->pelaporan_path = $data['pelaporan']->store('kelengkapan_files');
        }
        if (isset($data['absensi']) && is_object($data['absensi'])) {
            $record->absensi_path = $data['absensi']->store('kelengkapan_files');
        }
        if (isset($data['surat_ijin']) && is_object($data['surat_ijin'])) {
            $record->surat_ijin_path = $data['surat_ijin']->store('kelengkapan_files');
        }
        if (isset($data['contoh_spesimen']) && is_object($data['contoh_spesimen'])) {
            $record->contoh_spesimen_path = $data['contoh_spesimen']->store('kelengkapan_files');
        }
        $record->save();

        Notification::make()
            ->title('Kelengkapan dokumen berhasil disimpan')
            ->success()
            ->send();
    })
    ->visible(fn ($record) => in_array($record->status, ['Pengelolaan', 'Menunggu Verifikasi II', 'Terbit STTP'])),
        Tables\Actions\Action::make('dokumentasi')
            ->label('Dokumentasi')
            ->icon('heroicon-o-camera')
    ->url(fn ($record) => route('bangkom.dokumentasi', $record))
    ->visible(fn ($record) => in_array($record->status, ['Pengelolaan', 'Menunggu Verifikasi II', 'Terbit STTP'])),
        Tables\Actions\Action::make('peserta')
            ->label('Peserta')
            ->icon('heroicon-o-users')
    ->url(fn ($record) => route('bangkom.peserta', $record))
    ->visible(fn ($record) => in_array($record->status, ['Pengelolaan', 'Menunggu Verifikasi II', 'Terbit STTP'])),
        Tables\Actions\Action::make('ubah_status')
            ->label('Ubah status')
            ->icon('heroicon-o-pencil-square')
            ->form([
Forms\Components\Select::make('status')
    ->label('Status')
    ->options([
        'Draft' => 'Draft',
        'Menunggu Verifikasi I' => 'Menunggu Verifikasi I',
        'Pengelolaan' => 'Pengelolaan',
        'Menunggu Verifikasi II' => 'Menunggu Verifikasi II',
        'Terbit STTP' => 'Terbit STTP',
    ])
    ->required(),
                Forms\Components\Textarea::make('catatan')
                    ->label('Catatan')
                    ->rows(3),
            ])
            ->modalHeading('Ubah Status')
            ->modalSubmitActionLabel('Simpan')
            ->action(function ($record, array $data) {
                $oldStatus = $record->status;
                $record->status = $data['status'];
                $record->save();

                // Save status history
                $record->statusHistories()->create([
                    'user_id' => auth()->id(),
                    'old_status' => $oldStatus,
                    'new_status' => $data['status'],
                    'catatan' => $data['catatan'] ?? null,
                    'changed_at' => now(),
                ]);

                Notification::make()
                    ->title('Status berhasil diubah')
                    ->success()
                    ->send();
            })
    ->visible(fn ($record) => in_array($record->status, ['Draft', 'Menunggu Verifikasi I', 'Pengelolaan', 'Menunggu Verifikasi II', 'Terbit STTP'])),
Tables\Actions\Action::make('ajukan_permohonan')
    ->label('Dokumen Permohonan')
    ->icon('heroicon-o-paper-airplane')
    ->form([
        Forms\Components\FileUpload::make('file_permohonan')
            ->label('File Permohonan')
            ->required()
            ->maxSize(102400) // 100MB in KB
            ->acceptedFileTypes(['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'image/jpeg'])
            ->helperText('Ukuran file maksimum: 100MB. Format yang diizinkan: PDF, DOCX, XLSX, PPTX, JPEG.'),
        Forms\Components\Checkbox::make('agreement')
            ->label('Dengan ini saya menyetujui bahwa data yang saya isi adalah benar dan dapat dipercaya.')
            ->required(),
    ])
    ->modalHeading('Dokumen Permohonan')
    ->modalSubmitActionLabel('Simpan')
    ->action(function ($record, array $data) {
        // Save uploaded file path to related model or field
        if (isset($data['file_permohonan']) && is_object($data['file_permohonan'])) {
            $filePath = $data['file_permohonan']->store('permohonan_files');
            // Assuming Bangkom model has file_permohonan_path attribute or relation
            $record->file_permohonan_path = $filePath;
        }
        $record->status = 'Menunggu Verifikasi';
        $record->save();

        Notification::make()
            ->title('Permohonan berhasil diajukan')
            ->success()
            ->send();
    })
    ->visible(fn ($record) => !in_array($record->status, ['Draft', 'Menunggu Verifikasi I']) && in_array($record->status, ['Draft', 'Menunggu Verifikasi', 'Submitted'])),
Tables\Actions\Action::make('verifikasi_terbit_sttp')
    ->label('Verifikasi dan Terbitkan STTP')
    ->icon('heroicon-o-check-circle')
    ->requiresConfirmation()
    ->modalHeading('Konfirmasi Verifikasi dan Terbit STTP')
    ->modalDescription('Apakah Anda yakin ingin memverifikasi dan menerbitkan STTP untuk kegiatan ini?')
    ->modalSubmitActionLabel('Ya, Terbitkan STTP')
    ->action(function ($record) {
        $oldStatus = $record->status;
        $record->status = 'Terbit STTP';
        $record->save();

        $record->statusHistories()->create([
            'user_id' => auth()->id(),
            'old_status' => $oldStatus,
            'new_status' => 'Terbit STTP',
            'catatan' => 'Verifikasi dan Terbit STTP',
            'changed_at' => now(),
        ]);

        Notification::make()
            ->title('STTP berhasil diterbitkan')
            ->success()
            ->send();
    })
    ->visible(fn ($record) => $record->status == 'Menunggu Verifikasi II'),
Tables\Actions\Action::make('sttp')
    ->label('STTP')
    ->icon('heroicon-o-document')
    ->url(fn ($record) => route('bangkom.downloadSttp', $record))
    ->visible(fn ($record) => $record->status == 'Terbit STTP'),
        Tables\Actions\Action::make('histori_status')
            ->label('Histori Status')
            ->icon('heroicon-o-clock')
            ->modalHeading('Histori Status')
            ->modalContent(function ($record) {
                $histories = $record->statusHistories()->with('user')->orderBy('changed_at', 'desc')->get();
                if ($histories->isEmpty()) {
                    return new HtmlString('Belum ada perubahan status.');
                }
                $content = '<table class="min-w-full divide-y divide-gray-200">';
                $content .= '<thead class="bg-gray-50">';
                $content .= '<tr>';
                $content .= '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>';
                $content .= '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Oleh</th>';
                $content .= '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Sebelum</th>';
                $content .= '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Menjadi</th>';
                $content .= '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>';
                $content .= '</tr>';
                $content .= '</thead>';
                $content .= '<tbody class="bg-white divide-y divide-gray-200">';
                foreach ($histories as $history) {
                    $content .= '<tr>';
                    $content .= '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . e(\Carbon\Carbon::parse($history->changed_at)->format('d/m/Y H:i:s')) . '</td>';
                    $content .= '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . e($history->user->name ?? 'Unknown') . '</td>';
                    $content .= '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . e($history->old_status ?? '-') . '</td>';
                    $content .= '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . e($history->new_status) . '</td>';
                    $content .= '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . e($history->catatan ?? '-') . '</td>';
                    $content .= '</tr>';
                }
                $content .= '</tbody>';
                $content .= '</table>';
                return new HtmlString($content);
            })
            ->modal()
            ->visible(fn ($record) => in_array($record->status, ['Draft', 'Menunggu Verifikasi I', 'Pengelolaan', 'Menunggu Verifikasi II', 'Terbit STTP'])),
        Tables\Actions\ViewAction::make()
            ->visible(fn ($record) => in_array($record->status, ['Draft', 'Menunggu Verifikasi I', 'Pengelolaan', 'Menunggu Verifikasi II', 'Terbit STTP'])),
        Tables\Actions\EditAction::make()
            ->visible(fn ($record) => in_array($record->status, ['Draft', 'Menunggu Verifikasi I', 'Pengelolaan', 'Menunggu Verifikasi II', 'Terbit STTP'])),
        Tables\Actions\DeleteAction::make()
            ->visible(fn ($record) => in_array($record->status, ['Draft', 'Menunggu Verifikasi I', 'Pengelolaan', 'Menunggu Verifikasi II', 'Terbit STTP'])),
        Tables\Actions\Action::make('restore')
            ->label('Restore')
            ->icon('heroicon-o-arrow-uturn-left')
            ->color('success')
            ->requiresConfirmation()
            ->visible(fn ($record) => in_array($record->status, ['Draft', 'Menunggu Verifikasi I', 'Pengelolaan', 'Menunggu Verifikasi II', 'Terbit STTP']))
            ->action(fn ($record) => $record->restore()),
        Tables\Actions\DeleteAction::make('force_delete')
            ->label('Force delete')
            ->color('danger')
            ->requiresConfirmation()
            ->visible(fn ($record) => in_array($record->status, ['Draft', 'Menunggu Verifikasi I', 'Pengelolaan', 'Menunggu Verifikasi II', 'Terbit STTP']))
            ->action(fn ($record) => $record->forceDelete()),
    ]),
])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBangkoms::route('/'),
            'create' => Pages\CreateBangkom::route('/create'),
            'edit' => Pages\EditBangkom::route('/{record}/edit'),
            'view' => Pages\ViewBangkom::route('/{record}'),
        ];
    }
}