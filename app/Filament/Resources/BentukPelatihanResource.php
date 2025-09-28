<?php

namespace App\Filament\Resources;

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
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\Grid;

class BentukPelatihanResource extends Resource
{
    protected static ?string $model = BentukPelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';
    
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Bentuk Pelatihan')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
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
                    ->label('Nama Bentuk Pelatihan')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListBentukPelatihans::route('/'),
            'create' => Pages\CreateBentukPelatihan::route('/create'),
            'edit' => Pages\EditBentukPelatihan::route('/{record}/edit'),
            'view' => Pages\ViewBentukPelatihan::route('/{record}'),
        ];
    }
}
