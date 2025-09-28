<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MentoringResource\Pages;
use App\Filament\Resources\MentoringResource\RelationManagers;
use App\Models\Mentoring;
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
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class MentoringResource extends Resource
{
    protected static ?string $model = Mentoring::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        TextInput::make('nama_mentoring')
                            ->label('Nama Mentoring')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        Select::make('instansi_id')
                            ->label('Instansi')
                            ->relationship('instansi', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('widyaiswara_id')
                            ->label('Widyaiswara')
                            ->relationship('widyaiswara', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required(),
                        DatePicker::make('tanggal_berakhir')
                            ->label('Tanggal Berakhir')
                            ->required(),
                        TextInput::make('kuota')
                            ->label('Kuota')
                            ->numeric()
                            ->required(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('draft')
                            ->required(),
                        Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->maxLength(65535)
                            ->columnSpan(2),
                        Textarea::make('persyaratan')
                            ->label('Persyaratan')
                            ->maxLength(65535)
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
                Tables\Columns\TextColumn::make('nama_mentoring')
                    ->label('Nama Mentoring')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('instansi.name')
                    ->label('Instansi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('widyaiswara.name')
                    ->label('Widyaiswara')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_berakhir')
                    ->label('Tanggal Berakhir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kuota')
                    ->label('Kuota')
                    ->numeric()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => 'draft',
                        'success' => 'published',
                        'danger' => 'cancelled',
                    ]),
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
            'index' => Pages\ListMentorings::route('/'),
            'create' => Pages\CreateMentoring::route('/create'),
            'edit' => Pages\EditMentoring::route('/{record}/edit'),
            'view' => Pages\ViewMentoring::route('/{record}'),
        ];
    }
}
