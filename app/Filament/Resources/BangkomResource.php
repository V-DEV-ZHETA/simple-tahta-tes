<?php

namespace App\Filament\Resources;

use App\Enums\BangkomStatus;
use App\Filament\Resources\BangkomResource\Pages;
use App\Models\Bangkom;
use App\Services\BangkomPrintService;
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
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Illuminate\Database\Eloquent\Model;

class BangkomResource extends Resource
{
    protected static ?string $model = Bangkom::class;

    protected static ?string $navigationLabel = 'Bangkom';
    protected static ?string $modelLabel = 'Bangkom';
    protected static ?string $pluralModelLabel = 'Bangkom';
    protected static ?string $slug = 'bangkoms';
    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    /**
     * Define the form schema array. This is based on the structure provided in your request.
     * Note: I've added back the 'users_id' field but commented it out, as it was in your original code
     * but absent in your requested schema. I kept it here as a reminder/option.
     * I also adjusted the 'kurikulum' to use Forms\Components\Repeater as requested.
     */
    protected static function getFormSchema(): array
    {
        return [
            Wizard::make([
                Step::make('Kegiatan')
                    ->columns(12)
                    ->schema([
                        // I've removed the 'users_id' Select as requested in the target snippet.
                        /*
                        Forms\Components\Select::make('users_id')
                            ->label('Pelaksana')
                            ->relationship('user', 'name', fn($query) => $query->whereHas('roles', fn($q) => $q->where('name', 'pelaksana')))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(12),
                        */
                        Forms\Components\Select::make('instansi_id')
                            ->label('Instansi Pelaksana')
                            ->relationship('instansi', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(12),
                        Forms\Components\TextInput::make('unit_kerja')
                            ->label('Unit Kerja / Perangkat Daerah Pelaksana')
                            ->required()
                            ->columnSpan(12),
                        Forms\Components\TextInput::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(12),
                        Forms\Components\Select::make('jenis_pelatihan_id')
                            ->label('Jenis Pelatihan')
                            ->relationship('jenisPelatihan', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(12),
                        Forms\Components\Select::make('bentuk_pelatihan_id')
                            ->label('Bentuk Pelatihan')
                            ->relationship('bentukPelatihan', 'bentuk')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(12),
                        Forms\Components\Select::make('sasaran_id')
                            ->label('Sasaran')
                            ->relationship('sasaran', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(12),
                    ]),
                Step::make('Waktu, Tempat dan Kuota')
                    ->columns(12)
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->minDate(now())
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->columnSpan(6), // Use half span for dates
                        Forms\Components\DatePicker::make('tanggal_selesai') // Changed 'tanggal_berakhir' back to 'tanggal_selesai' to match model/db
                            ->label('Tanggal Berakhir')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->minDate(now())
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->columnSpan(6), // Use half span for dates
                        Forms\Components\TextInput::make('tempat')
                            ->label('Tempat')
                            ->placeholder('Venue/Tempat Kegiatan')
                            ->required()
                            ->suffixIcon('heroicon-o-map-pin')
                            ->columnSpan(12),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->required()
                            ->columnSpan(12),
                        Forms\Components\TextInput::make('kuota')
                            ->label('Kuota')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->suffixIcon('heroicon-o-users')
                            ->columnSpan(12), // Changed back to 12 as 2 is too small
                            // Removed dehydrateStateUsing as numeric does this better
                    ]),
                Step::make('Panitia')
                    ->columns(12)
                    ->schema([
                        Forms\Components\TextInput::make('nama_panitia')
                            ->label('Nama Panitia')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(12),
                        Forms\Components\TextInput::make('no_telp') // Changed 'telepon_panitia' back to 'no_telp' to match original code/model
                            ->label('Telepon Panitia')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(12),
                    ]),
                Step::make('Kurikulum')
                    ->schema([
                        Forms\Components\Repeater::make('kurikulum')
                            ->label('Kurikulum')
                            ->schema([
                                Forms\Components\TextInput::make('Narasumber') // Kept 'Narasumber' with capital N to match original TableRepeater structure
                                    ->label('Narasumber')
                                    ->required()
                                    ->columnSpan(4),
                                Forms\Components\TextInput::make('materi')
                                    ->label('Materi')
                                    ->required()
                                    ->columnSpan(4),
                                Forms\Components\TextInput::make('jam_pelajaran')
                                    ->label('Jam Pelajaran')
                                    ->numeric() // Ensures it's a number
                                    ->required()
                                    ->columnSpan(4),
                            ])
                            ->columns(12)
                            ->addActionLabel('Tambah Kurikulum')
                            ->defaultItems(1)
                            ->columnSpan(12),
                    ]),
                Step::make('Deskripsi Kegiatan & Persyaratan')
                    ->schema([
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpan(12),
                        Forms\Components\Textarea::make('persyaratan')
                            ->label('Persyaratan')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpan(12),
                    ]),
            ])
            ->submitAction(new HtmlString('
                <button
                    type="submit"
                    class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-warning-600 hover:bg-warning-500 focus:bg-warning-700 focus:ring-offset-warning-700"
                >
                    Submit
                </button>
            '))
            ->columnSpanFull()
            ->skippable(), // Kept the skipable function
            // Removed startOnStep(5) and extraAttributes, as they are non-standard/likely for a specific use case
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
                Tables\Columns\TextColumn::make('no')
                    ->rowIndex()
                    ->label('No'),
                Tables\Columns\TextColumn::make('nama_kegiatan')
                    ->label('Nama Kegiatan')
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        $kode = $record->kode_kegiatan ?? '';
                        return $kode ? "{$state}<br><span
                        style='display:inline-block; background-color:rgba(255, 244, 230, 0.9);color:#FF6600; border:1px;
                         border-radius:6px;font-size:12px;font-weight:600;padding:0px 5px;margin-top:4px;'>{$kode}</span>" : $state;
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('jenisPelatihan.name')
                    ->label('Jenis Pelatihan')
                    ->searchable()
                    ->sortable()
                    ->default('-')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Pelatihan')
                    ->sortable()
                    ->getStateUsing(fn(\App\Models\Bangkom $record) => $record->tanggal_mulai) // Used for sorting
                    ->formatStateUsing(function ($state, $record) {
                        if (empty($record->tanggal_mulai) || empty($record->tanggal_selesai)) {
                            return "-";
                        }

                        $mulai = Carbon::parse($record->tanggal_mulai);
                        $akhir = Carbon::parse($record->tanggal_selesai);

                        $formatMulai = $mulai->translatedFormat("d M");
                        $formatAkhir = $akhir->translatedFormat("d M");

                        return "$formatMulai <span class='text-gray-500 text-sm'>s/d</span> $formatAkhir";
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('kuota')
                    ->label('Kuota')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => $state instanceof BangkomStatus ? $state->getColor() : BangkomStatus::from($state)->getColor())
                    ->icon(fn($state) => $state instanceof BangkomStatus ? $state->getIcon() : BangkomStatus::from($state)->getIcon())
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(BangkomStatus::class),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ActionGroup::make([
                    // Action untuk verifikasi dan terbitkan STTP pada status Menunggu Verifikasi II
                    Tables\Actions\Action::make('verifikasiTerbitkanSTTP')
                        ->label('Verifikasi & Terbitkan STTP')
                        ->icon('heroicon-o-document-check')
                        ->color('success')
                        ->visible(fn(Bangkom $record): bool => $record->status === BangkomStatus::MenungguVerifikasiII)
                        ->form([
                            // Form upload file STTP
                            Forms\Components\FileUpload::make('file_sttp')
                                ->label('File STTP')
                                ->required()
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                    'application/msword',
                                ])
                                ->maxSize(102400)
                                ->helperText('*Ukuran file maksimum: 100MB. Format yang diijinkan: PDF, DOCX.')
                                ->directory('sttp')
                                ->visibility('private')
                                ->downloadable()
                                ->openable()
                                ->previewable(),
                        ])
                        ->modalHeading('Verifikasi & Terbitkan STTP')
                        ->modalDescription('Upload file STTP dan konfirmasi penerbitan.')
                        ->modalSubmitActionLabel('Terbitkan STTP')
                        ->action(function (Bangkom $record, array $data) {
                            // Update status ke Terbit STTP dan simpan file
                            $record->update([
                                'status' => BangkomStatus::TerbitSTTP,
                                'file_sttp' => $data['file_sttp'],
                            ]);

                            // Kirim notifikasi sukses
                            Notification::make()
                                ->title('STTP berhasil diterbitkan')
                                ->success()
                                ->send();
                        }),

                        // Action untuk melihat dan update file STTP pada status Terbit STTP
                    Tables\Actions\Action::make('lihatSTTP')
                        ->label('STTP')
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->visible(fn(Bangkom $record): bool => $record->status === BangkomStatus::TerbitSTTP)
                        ->form([
                            // Form upload file STTP dengan default value dari record
                            Forms\Components\FileUpload::make('file_sttp')
                                ->label('File STTP')
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                    'application/msword',
                                ])
                                ->maxSize(102400)
                                ->helperText('*Ukuran file maksimum: 100MB. Format yang diijinkan: PDF, DOCX.')
                                ->directory('sttp')
                                ->visibility('private')
                                ->downloadable()
                                ->openable()
                                ->previewable()
                                ->default(fn(Bangkom $record) => $record->file_sttp),
                        ])
                        ->modalHeading('File STTP')
                        ->modalDescription('Lihat atau update file STTP yang telah diupload.')
                        ->modalSubmitActionLabel('Update STTP')
                        ->action(function (Bangkom $record, array $data) {
                            // Update file STTP
                            $record->update([
                                'file_sttp' => $data['file_sttp'],
                            ]);

                            // Kirim notifikasi sukses
                            Notification::make()
                                ->title('File STTP berhasil diupdate')
                                ->success()
                                ->send();
                        }),

                    // Actions from original class (cetakPermohonan, ajukanPermohonan, etc.) are kept for functionality
                    Tables\Actions\Action::make('cetakPermohonan')
                        ->label('Cetak Permohonan')
                        ->icon('heroicon-o-printer')
                        ->color('gray')
                        ->visible(
                            fn(Bangkom $record): bool =>
                            in_array($record->status, [
                                BangkomStatus::Draft,
                                BangkomStatus::MenungguVerifikasi
                            ])
                        )
                        ->action(function (Bangkom $record) {
                            try {
                                $printService = new BangkomPrintService();
                                $filePath = $printService->cetakPermohonan($record);

                                Notification::make()
                                    ->title('Permohonan berhasil dicetak')
                                    ->success()
                                    ->send();

                                return response()->download($filePath)->deleteFileAfterSend(true);
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Gagal mencetak permohonan')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                    // Action untuk mengajukan permohonan dari status Draft
                    Tables\Actions\Action::make('ajukanPermohonan')
                        ->label('Ajukan Permohonan')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('gray')
                        ->modalHeading('Ajukan Permohonan')
                        ->form([
                            // Form upload file permohonan
                            Forms\Components\FileUpload::make('file_permohonan')
                                ->label('File Permohonan')
                                ->required()
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                    'application/msword',
                                    'application/vnd.ms-excel',
                                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                    'application/vnd.ms-powerpoint',
                                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                    'image/jpeg',
                                    'image/png',
                                ])
                                ->maxSize(102400)
                                ->helperText('*Ukuran file maksimum: 100MB. Format yang diijinkan: PDF, DOCX, XLSX, PPTX, JPEG.')
                                ->directory('permohonan')
                                ->visibility('private')
                                ->downloadable()
                                ->openable()
                                ->previewable(),

                            // Checkbox persetujuan
                            Forms\Components\Checkbox::make('persetujuan')
                                ->label('Dengan ini saya menyetujui bahwa data yang saya isi adalah benar dan dapat dipercaya.')
                                ->required()
                                ->accepted()
                                ->validationMessages([
                                    'accepted' => 'Anda harus menyetujui pernyataan ini untuk melanjutkan.',
                                ]),
                        ])
                        ->modalSubmitActionLabel('Submit')
                        ->modalCancelActionLabel('Tutup')
                        ->visible(fn(Bangkom $record): bool => $record->status === BangkomStatus::Draft)
                        ->action(function (Bangkom $record, array $data) {
                            // Update status ke Menunggu Verifikasi dan simpan file
                            $record->update([
                                'status' => BangkomStatus::MenungguVerifikasi,
                                'file_permohonan' => $data['file_permohonan'],
                            ]);
                        }),

                    // Action untuk melihat dan update dokumen permohonan
                    Tables\Actions\Action::make('DokumenPermohonan')
                        ->label('Dokumen Permohonan')
                        ->icon('heroicon-o-document')
                        ->color('gray')
                        ->modalHeading('Dokumen Permohonan')
                        ->form([
                            // Form upload file permohonan dengan default value dari record
                            Forms\Components\FileUpload::make('file_permohonan')
                                ->label('File Permohonan')
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                    'application/msword',
                                    'application/vnd.ms-excel',
                                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                    'application/vnd.ms-powerpoint',
                                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                    'image/jpeg',
                                    'image/png',
                                ])
                                ->maxSize(102400)
                                ->helperText('*Ukuran file maksimum: 100MB. Format yang diijinkan: PDF, DOCX, XLSX, PPTX, JPEG.')
                                ->directory('permohonan')
                                ->visibility('private')
                                ->downloadable()
                                ->openable()
                                ->previewable()
                                ->default(fn(Bangkom $record) => $record->file_permohonan),
                        ])
                        ->modalSubmitActionLabel('Update Dokumen')
                        ->modalCancelActionLabel('Tutup')
                        ->action(function (Bangkom $record, array $data) {
                            // Update file permohonan
                            $record->update([
                                'file_permohonan' => $data['file_permohonan'],
                            ]);

                            // Kirim notifikasi sukses
                            Notification::make()
                                ->title('Dokumen berhasil diupdate')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('KelengkapanDokumen')
                        ->label('Kelengkapan Dokumen')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('gray')
                        ->modalHeading('Kelengkapan Permohonan')
                        ->form([
                            Forms\Components\FileUpload::make('bahan_tayang')
                                ->label('Bahan Tayang')
                                ->acceptedFileTypes(['application/pdf'])
                                ->maxSize(102400)
                                ->helperText('*Ukuran file maksimum: 100MB. Format yang diizinkan: PDF.')
                                ->directory('kelengkapan/bahan-tayang')
                                ->visibility('private')
                                ->downloadable()
                                ->openable()
                                ->previewable(),

                            Forms\Components\FileUpload::make('pelaporan')
                                ->label('Pelaporan')
                                ->acceptedFileTypes(['application/pdf'])
                                ->maxSize(102400)
                                ->helperText('*Ukuran file maksimum: 100MB. Format yang diizinkan: PDF.')
                                ->directory('kelengkapan/pelaporan')
                                ->visibility('private')
                                ->downloadable()
                                ->openable()
                                ->previewable(),

                            Forms\Components\FileUpload::make('absensi')
                                ->label('Absensi')
                                ->acceptedFileTypes(['application/pdf'])
                                ->maxSize(102400)
                                ->helperText('*Ukuran file maksimum: 10MB. Format yang diizinkan: PDF.')
                                ->directory('kelengkapan/absensi')
                                ->visibility('private')
                                ->downloadable()
                                ->openable()
                                ->previewable(),

                            Forms\Components\FileUpload::make('surat_ttd')
                                ->label('Surat ttd Pengesahan Spesimen TTD Kepala')
                                ->acceptedFileTypes(['application/pdf'])
                                ->maxSize(102400)
                                ->helperText('*Ukuran file maksimum: 10MB. Format yang diizinkan: PDF.')
                                ->directory('kelengkapan/surat-ttd')
                                ->visibility('private')
                                ->downloadable()
                                ->openable()
                                ->previewable(),

                            Forms\Components\FileUpload::make('contoh_sertifikat')
                                ->label('Contoh Spesimen TTD Kepala')
                                ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg'])
                                ->maxSize(102400)
                                ->helperText('*Ukuran file maksimum: 10MB. Format: PDF, PNG. Resolusi layar HD.')
                                ->directory('kelengkapan/contoh-sertifikat')
                                ->visibility('private')
                                ->downloadable()
                                ->openable()
                                ->previewable(),
                        ])
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup'),


                    Tables\Actions\Action::make('dokumentasi')
                        ->label('Dokumentasi')
                        ->icon('heroicon-o-camera')
                        ->color('gray')
                        ->visible(fn(Bangkom $record): bool => $record->status === BangkomStatus::Pengelolaan)
                        ->url(fn(Bangkom $record): string => BangkomResource::getUrl('dokumentasi', ['record' => $record])),

                    Tables\Actions\Action::make('peserta')
                        ->label('Peserta')
                        ->icon('heroicon-o-users')
                        ->color('gray')
                        ->visible(fn(Bangkom $record): bool => in_array($record->status, [
                            BangkomStatus::Pengelolaan,
                            BangkomStatus::MenungguVerifikasiII,
                            BangkomStatus::TerbitSTTP,
                        ]))
                        ->url(fn(Bangkom $record): string => BangkomResource::getUrl('peserta', ['record' => $record])),

                    Tables\Actions\Action::make('ubahStatus')
                        ->label('Ubah Status')
                        ->icon('heroicon-o-arrow-path')
                        ->modalHeading('Ubah Status')
                        ->modalDescription('Are you sure you would like to do this?')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    BangkomStatus::Draft->value => 'Draft',
                                    BangkomStatus::MenungguVerifikasi->value => 'Menunggu Verifikasi I',
                                    BangkomStatus::Pengelolaan->value => 'Pengolalaan',
                                    BangkomStatus::MenungguVerifikasiII->value => 'Menunggu Verifikasi II',
                                    BangkomStatus::TerbitSTTP->value => 'Terbit STTP',
                                ])
                                ->default(fn(Bangkom $record): string => $record->status->value)
                                ->required()
                                ->native(false)
                                ->selectablePlaceholder(false),
                            Forms\Components\Textarea::make('catatan')
                                ->label('Catatan')
                                ->rows(3)
                                ->helperText('Conton Permintaan perbaikan usulan dan operator.'),
                        ])
                        ->requiresConfirmation()
                        ->action(function (Bangkom $record, array $data) {
                            $oldStatus = $record->status;
                            $newStatus = BangkomStatus::from($data['status']);

                            // Simpan status lama untuk histori
                            $oldStatusValue = $oldStatus instanceof BangkomStatus ? $oldStatus->value : $oldStatus;
                            $newStatusValue = $newStatus instanceof BangkomStatus ? $newStatus->value : $newStatus;

                            $record->update([
                                'status' => $newStatus,
                                'catatan' => $data['catatan'] ?? null,
                            ]);

                            // Catat histori status secara manual untuk memastikan tercatat
                            \App\Models\StatusHistory::create([
                                'bangkom_id' => $record->getKey(),
                                'status_sebelum' => $oldStatusValue,
                                'status_menjadi' => $newStatusValue,
                                'new_status' => $newStatusValue,
                                'users_id' => Auth::id(),
                                'oleh' => Auth::user()?->name ?? 'System',
                                'catatan' => $data['catatan'] ?? 'Status diubah manual',
                            ]);

                            Notification::make()
                                ->title('Status berhasil diubah')
                                ->body("Status diubah dari {$oldStatus->getLabel()} menjadi {$newStatus->getLabel()}")
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('historiStatus')
                        ->label('Histori Status')
                        ->icon('heroicon-o-clock')
                        ->color('gray')
                        ->modalHeading('Histori Status')
                        ->modalContent(fn(Model $record) => new HtmlString(Blade::render(<<<'BLADE'
                        <livewire:modal.histori-status-modal
                            :bangkom="$bangkom" />
                        BLADE, [
                            'bangkom' => $record,
                        ])))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup'),

                    

                    


                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->color('gray'),
                    Tables\Actions\DeleteAction::make()
                        ->visible(fn(Bangkom $record): bool => $record->status === BangkomStatus::Draft),
                    RestoreAction::make(),
                    Tables\Actions\Action::make('forceDelete')
                        ->label('Force Delete')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Permanen')
                        ->modalDescription('Apakah Anda yakin ingin menghapus data ini secara permanen?')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->action(function (Bangkom $record) {
                            $record->forceDelete();

                            Notification::make()
                                ->title('Data berhasil dihapus permanen')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBangkoms::route('/'),
            'create' => Pages\CreateBangkom::route('/create'),
            'edit' => Pages\EditBangkom::route('/{record}/edit'),
            'dokumentasi' => Pages\ManageDokumentasi::route('/{record}/dokumentasi'),
            'peserta' => Pages\ManagePeserta::route('/{record}/peserta'),
            'view' => Pages\ViewBangkom::route('/{record}/view'),
        ];
    }
}