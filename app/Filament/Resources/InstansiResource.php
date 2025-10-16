<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstansiResource\Pages;
use App\Models\Instansi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\Grid;

class InstansiResource extends Resource
{
    protected static ?string $model = Instansi::class;

    protected static ?string $navigationIcon = 'carbon-building';

    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat')
                            ->maxLength(65535)
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telepon')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('website')
                            ->label('Website')
                            ->maxLength(255),
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
                    ->formatStateUsing(function ($state, $record) {
                        $slug = $record->address ? "<br><small>Alamat :{$record->address}</small>" : "";
                        return $state . $slug;
                    })
                    ->label('Nama')
                    ->sortable()
                    ->searchable()
                    ->grow()
                    ->html(),
                Tables\Columns\TextColumn::make('address') // Use a valid attribute or no attribute
                    ->label('Kontak')
                    ->formatStateUsing(function ($record) {
                        $phone = $record->phone ? "No. Telepon : {$record->phone}" : "-";
                        $email = $record->email ? "Email : {$record->email}" : "";
                        $website = $record->website ? "Website : {$record->website}" : "";
                        $contact =$phone;
                        if ($email) {
                            $contact .= "<br>" . $email;
                        }
                        if ($website) {
                            $contact .= "<br>" . $website;
                        }
                        return $contact;
                    })
                    ->html()
                    ->sortable(false),
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
            'index' => Pages\ListInstansis::route('/'),
        ];
    }
}
