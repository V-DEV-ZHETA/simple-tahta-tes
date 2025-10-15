<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WidyaiswaraResource\Pages;
use App\Filament\Resources\WidyaiswaraResource\RelationManagers;
use App\Models\Widyaiswara;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\Grid;

class WidyaiswaraResource extends Resource
{
    protected static ?string $model = Widyaiswara::class;

    protected static ?string $navigationIcon = 'uiw-user';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        // ...existing code...
            return $form
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama')
                                ->placeholder('Masukkan nama lengkap')
                                ->required()
                                ->maxLength(255)
                                ->helperText('Nama lengkap sesuai identitas.')
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('nip')
                                ->label('NIP')
                                ->placeholder('Masukkan NIP')
                                ->required()
                                ->maxLength(50)
                                ->unique(ignoreRecord: true)
                                ->mask('999999999999999999')
                                ->helperText('Nomor Induk Pegawai, harus unik.')
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('email')
                                ->label('Email')
                                ->placeholder('contoh@email.com')
                                ->email()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->helperText('Email aktif, harus unik.'),
                            Forms\Components\TextInput::make('phone')
                                ->label('Telepon')
                                ->placeholder('08xxxxxxxxxx')
                                ->maxLength(20)
                                ->mask('999999999999')
                                ->helperText('Nomor telepon yang dapat dihubungi.'),
                            Forms\Components\Select::make('gender')
                                ->label('Jenis Kelamin')
                                ->options([
                                    'L' => 'Laki-laki',
                                    'P' => 'Perempuan',
                                ])
                                ->required()
                                ->helperText('Pilih jenis kelamin.'),
                            Forms\Components\DatePicker::make('birthdate')
                                ->label('Tanggal Lahir')
                                ->helperText('Tanggal lahir sesuai identitas.'),
                        ]),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('No')
                    ->sortable()
                    ->toggleable(false),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListWidyaiswaras::route('/'),
        ];
    }
}
