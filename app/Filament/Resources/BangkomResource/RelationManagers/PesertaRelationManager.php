<?php

namespace App\Filament\Resources\BangkomResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PesertaRelationManager extends RelationManager
{
    protected static string $relationship = 'peserta';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('nip')
                    ->label('NIP')
                    ->maxLength(255),

                Forms\Components\TextInput::make('jabatan')
                    ->maxLength(255),

                Forms\Components\TextInput::make('instansi')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Peserta'),

                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP'),

                Tables\Columns\TextColumn::make('jabatan'),

                Tables\Columns\TextColumn::make('instansi'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
