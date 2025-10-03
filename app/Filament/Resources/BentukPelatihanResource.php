<?php

namespace App\Filament\Resources;

use App\Enums\JalurPelatihan;
use App\Filament\Resources\BentukPelatihanResource\Pages;
use App\Filament\Resources\BentukPelatihanResource\RelationManagers;
use App\Models\BentukPelatihan;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BentukPelatihanResource extends Resource
{
    protected static ?string $model = BentukPelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Bentuk pelatihan';
    protected static ?string $pluralLabel = 'Bentuk Pelatihan';
    protected static ?string $slug = 'Bentuk-Pelatihan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jalur')
                    ->label('Jalur')
                    ->options(JalurPelatihan::class)
                    ->required(),
                Forms\Components\TextInput::make('bentuk')
                    ->label('Bentuk Pelatihan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(3),
            ])->columns(1); ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('No')
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('bentuk')
                    ->label('Bentuk Pelatihan')
                    ->searchable()
                    ->sortable()
                    ->grow(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),
            ])
            ->defaultSort('id','asc')
            ->defaultGroup('jalur')
            ->filters([
                Tables\Filters\SelectFilter::make('jalur')
                    ->label('Filter Jalur Pelatihan')
                    ->options([
                        JalurPelatihan::KLASIKAL->value => JalurPelatihan::KLASIKAL->getLabel(),
                        JalurPelatihan::NON_KLASIKAL->value => JalurPelatihan::NON_KLASIKAL->getLabel(),
                    ]),
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
            ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('jalur')
                    ->label('Jalur'),
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
            'index' => Pages\ListBentukPelatihans::route('/'),
            // 'create' => Pages\CreateBentukPelatihan::route('/create'),
            // 'edit' => Pages\EditBentukPelatihan::route('/{record}/edit'),
        ];
    }
}
