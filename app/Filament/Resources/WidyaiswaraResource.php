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

class WidyaiswaraResource extends Resource
{
    protected static ?string $model = Widyaiswara::class;

    protected static ?string $navigationIcon = 'phosphor-user';
    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Widyaiswara';

    protected static ?string $pluralLabel = 'Widyaiswara';

    protected static ?string $slug = 'widyaiswaras';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nip')
                    ->label('NIP')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('satker')
                    ->label('Satker')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Fieldset::make('Kontak')
                    ->schema([
                        Forms\Components\TextInput::make('telpon')
                            ->label('Telepon')
                            ->tel()
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true, table: 'widyaiswaras'),
                    ])->columns(1),
                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->rows(3),
            ])->columns(1); ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama & satker')
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        $satker = $record->satker ? "<div class='text-xs text-gray-500'>Satker : {$record->satker}</div>" : '';
                        return "<div>{$state}</div>" . $satker;
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('kontak')
                    ->label('Kontak')
                    ->getStateUsing(function ($record) {
                        $kontak = [];

                        if ($record->telpon) {
                            $kontak[] = "<span class=\"text-xs\"> No Tlp:</span> <span class=\"text-xs text-gray-500\">  {$record->telpon} </span>";
                        }

                        if ($record->email) {
                            $kontak[] = "<span class=\"text-xs\"> Email:</span> <span class=\"text-xs text-gray-500\">  {$record->email} </span>";
                        }

                        return implode('<br>', $kontak);
                    })
                    ->html(),
            ])
            ->filters([
                //
            ])
            ->actions([
            Tables\Actions\ActionGroup::make([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalWidth('lg'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalWidth('md')
                    ->requiresConfirmation(),
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
            // 'create' => Pages\CreateWidyaiswara::route('/create'),
            // 'edit' => Pages\EditWidyaiswara::route('/{record}/edit'),
        ];
    }
}
