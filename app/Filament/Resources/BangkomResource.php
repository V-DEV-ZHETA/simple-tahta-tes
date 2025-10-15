<?php

namespace App\Filament\Resources;

use App\Enums\BangkomStatus;
use App\Filament\Resources\BangkomResource\Pages;
use App\Filament\Resources\BangkomResource\RelationManagers;
use App\Models\Bangkom;
use App\Services\BangkomPrintService;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\DateColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;

class BangkomResource extends Resource
{
    protected static ?string $model = Bangkom::class;

    protected static ?string $navigationLabel = 'Bangkom';
    protected static ?string $modelLabel = 'Bangkom';
    protected static ?string $pluralModelLabel = 'Bangkom';
    protected static ?string $slug = 'bangkom';
    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Kegiatan')
                        ->schema([
                            Select::make('users_id')
                                ->label('pelaksana')
                                ->relationship(
                                    name: 'user',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn($query) => $query->whereHas('roles', fn($q) => $q->where('name', 'pelaksana'))
                                )
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('instansi_id')
                                ->label('Instansi Pelaksana')
                                ->relationship('instansi', 'nama_instansi')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('unit_kerja')
                                ->required()
                                ->label('Unit Kerja / Perangkat Daerah Pelaksana*'),
                            TextInput::make('nama_kegiatan')
                                ->required()
                                ->label('Nama Kegiatan*')
                                ->maxLength(255),
                            Select::make('jenis_pelatihan_id')
                                ->label('Jenis Pelatihan*')
                                ->relationship('jenisPelatihan', 'nama')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('bentuk_pelatihan_id')
                                ->label('Bentuk Pelatihan')
                                ->relationship('bentukPelatihan', 'label')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('sasaran_id')
                                ->label('Sasaran')
                                ->relationship('sasaran', 'nama')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])
                        ->columnSpanFull(),
                    Wizard\Step::make('Waktu, Tempat dan Kouta')
                        ->schema([
                            DatePicker::make('tanggal_mulai')
                                ->label('Tanggal Mulai')
                                ->required()
                                ->displayFormat('d/m/Y')
                                ->minDate(now())
                                ->suffixIcon('heroicon-o-calendar')
                                ->native(false),
                            DatePicker::make('tanggal_selesai')
                                ->label('Tanggal Berakhir')
                                ->required()
                                ->displayFormat('d/m/Y')
                                ->minDate(now())
                                ->suffixIcon('heroicon-o-calendar')
                                ->native(false),
                            TextInput::make('tempat')
                                ->label('Tempat')
                                ->required()
                                ->suffixIcon('heroicon-o-map-pin'),
                            Textarea::make('alamat')
                                ->label('Alamat')
                                ->required(),
                            TextInput::make('kuota')
                                ->label('Kuota')
                                ->numeric()
                                ->minValue(1)
                                ->default(1)
                                ->suffixIcon('heroicon-o-users')
                                ->required(),
                        ])
                        ->columnSpanFull(),
                    Wizard\Step::make('Panitia')
                        ->schema([
                            TextInput::make('nama_panitia')
                                ->required()
                                ->maxLength(255)
                                ->label('Nama Peserta'),
                            TextInput::make('no_telp')
                                ->required()
                                ->label('Telepon Panitia')
                                ->maxLength(255),
                        ])
                        ->columnSpanFull(),
                    Forms\Components\Wizard\Step::make('Kurikulum')
                        ->schema([
                            TableRepeater::make('kurikulum')
                                ->label('')
                                ->headers([
                                    Header::make('Narasumber')->label('Narasumber'),
                                    Header::make('materi')->label('Materi'),
                                    Header::make('jam_pelajaran')->label('Jam Pelajaran'),
                                ])
                                ->schema([
                                    TextInput::make('Narasumber')
                                        ->label('Isi Narasumber')
                                        ->required()
                                        ->columnSpan(1),

                                    TextInput::make('materi')
                                        ->label('Isi Materi')
                                        ->required()
                                        ->columnSpan(1),

                                    TextInput::make('jam_pelajaran')
                                        ->numeric(),
                                ])
                                ->addActionLabel('Tambah Kurikulum')
                                ->defaultItems(1)
                                ->columnSpan('full')
                        ]),
                    Wizard\Step::make('Deskripsi Kegiatan & Persyaratan')
                        ->schema([
                            Textarea::make('deskripsi')
                                ->label('Deskripsi')
                                ->rows(3)
                                ->maxLength(1000)
                                ->required(),
                            Textarea::make('persyaratan')
                                ->label('Persyaratan')
                                ->rows(3)
                                ->maxLength(1000)
                                ->required(),
                        ])->columnSpanFull(),

                ])
                    ->submitAction(new \Illuminate\Support\HtmlString('
                        <button
                            type="submit"
                            wire:click="create"
                            class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-warning-600 hover:bg-warning-500 focus:bg-warning-700 focus:ring-offset-warning-700"
                        >
                            Submit
                        </button>
                    '))
                    ->columnSpanFull()
                    ->Skippable(),
            ]);
    }

    public static function table(Table $table): Table
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
                        style='display:inline-block; background-color:rgba(255, 255, 255, 0.05);color:#d97706; border:1px solid #d97706;
                         border-radius:6px;font-size:12px;font-weight:600;padding:2px 8px;margin-top:4px;'>{$kode}</span>" : $state;
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('jenisPelatihan.nama')
                    ->label('Jenis Pelatihan')
                    ->searchable()
                    ->sortable()
                    ->default('-')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Pelatihan')
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->tanggal_mulai || !$record->tanggal_selesai) {
                            return "-";
                        }

                        $mulai = Carbon::parse($record->tanggal_mulai)->translatedFormat("d M");
                        $akhir = Carbon::parse($record->tanggal_selesai)->translatedFormat("d M Y");

                        return "$mulai<small> <span class='text-gray-500 text-sm'>s/d</span> </small> $akhir";
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

                    Tables\Actions\Action::make('ajukanPermohonan')
                        ->label('Ajukan Permohonan')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('gray')
                        ->modalHeading('Ajukan Permohonan')
                        ->form([
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
                            $record->update([
                                'status' => BangkomStatus::MenungguVerifikasi,
                                'file_permohonan' => $data['file_permohonan'],
                            ]);
                        }),
                    Tables\Actions\Action::make('DokumenPermohonan')
                        ->label('Dokumen Permohonan')
                        ->icon('heroicon-o-document')
                        ->color('gray')
                        ->modalHeading('Ajukan Permohonan')
                        ->form([
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
                        ])
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup')
                        ->action(function (Bangkom $record, array $data) {
                            $record->update([
                                'status' => BangkomStatus::MenungguVerifikasi,
                                'file_permohonan' => $data['file_permohonan'],
                            ]);
                        }),

                    Tables\Actions\Action::make('KelengkapanDokumen')
                        ->label('Kelengkapan Dokumen')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('gray')
                        ->modalHeading('Kelengkapan Permohonan')
                        ->form([
                            Forms\Components\FileUpload::make('bahan_tayang')
                                ->label('Bahan Tayang')
                                ->disabled()
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
                        ->url(fn(Bangkom $record): string => BangkomResource::getUrl('dokumentasi', ['record' => $record])),

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

                            $record->update([
                                'status' => $newStatus,
                                'catatan' => $data['catatan'] ?? null,
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
                    RestoreAction::make(),
                    Tables\Actions\EditAction::make()
                        ->color('gray')
                        ->visible(fn(Bangkom $record): bool => $record->status === BangkomStatus::Draft),
                    Tables\Actions\DeleteAction::make()
                        ->visible(fn(Bangkom $record): bool => $record->status === BangkomStatus::Draft),

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
            'view' => Pages\ViewBangkom::route('/{record}/view'),
        ];
    }
}