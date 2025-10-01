<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisPelatihanResource\Pages;
use App\Filament\Resources\JenisPelatihanResource\RelationManagers;
use App\Models\JenisPelatihan;
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

class JenisPelatihanResource extends Resource
{
    protected static ?string $model = JenisPelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Jenis Pelatihan')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('deskripsi')
                        ->columnSpan(1)
                        ->label('Deskripsi'),
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
                    ->toggleable(false)
                    ->getStateUsing(fn ($record, $rowLoop) => $rowLoop->iteration),
                Tables\Columns\TextColumn::make('name')
                    ->label('Jenis Pelatihan')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        $slug = $record->name ? "<br><small>Slug : /{$record->name}</small>" : "";
                        return $state . $slug;
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('deskripsi')
                ->label('Deskripsi')
                ->grow()
                ->sortable()
                ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
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
            'index' => Pages\ListJenisPelatihans::route('/'),
        ];
    }
}
