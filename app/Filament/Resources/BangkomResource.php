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
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class BangkomResource extends Resource
{
    protected static ?string $model = Bangkom::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    // ... (getFormSchema dan form function tidak berubah) ...
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
            //   ->startOnStep(5)
              ->extraAttributes(['style' => 'width: 100%; max-width: none;']),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema(static::getFormSchema());
    }

    // ... (table, columns, dan filters tidak berubah) ...
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
                        'gray' => 'Draft',
                        'primary' => ['Menunggu Verifikasi I', 'Menunggu Verifikasi II'],
                        'warning' => 'Pengelolaan',
                        'success' => 'Terbit STTP',
                        'secondary' => 'Verifikasi Berhasil',
                        'danger' => 'Cancelled',
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
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
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