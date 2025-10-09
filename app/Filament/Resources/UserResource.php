<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $navigationGroup = 'Pengaturan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('telepon')
                    ->label('Phone')
                    ->tel()
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),

                Forms\Components\Select::make('roles')
                    ->label('Roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->default(['Pelaksana'])
                    ->preload(), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('telepon')
                    ->label('Phone')
                    ->searchable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status_verifikasi')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        return $record->verified_at ? 'Terverifikasi' : 'Belum Terverifikasi';
                    })
                    ->color(function ($record) {
                        return $record->verified_at ? 'success' : 'danger';
                    })
                    ->description(function ($record) {
                        return $record->verified_at 
                            ? $record->verified_at->format('d M Y H:i:s') 
                            : null;
                    }),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('impersonate')
                    ->label('')
                    ->icon('fontisto-user-secret')
                    ->iconSize('lg')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Login sebagai User ini?')
                    ->modalDescription(fn ($record) => "Anda akan login sebagai {$record->name}. Anda dapat kembali ke akun admin dengan mengklik tombol 'Leave Impersonation'.")
                    ->modalSubmitActionLabel('Ya, Login')
                    ->action(function ($record) {
                        // Simpan ID admin yang sedang login
                        session()->put('impersonate', [
                            'admin_id' => Auth::id(),
                            'admin_name' => Auth::user()->name,
                        ]);
                        
                        // Login sebagai user target
                        Auth::loginUsingId($record->id);
                        
                        Notification::make()
                            ->title('Berhasil login sebagai ' . $record->name)
                            ->success()
                            ->send();
                        
                        // Redirect ke dashboard
                        return redirect('/bangkom');
                    })
                    ->visible(function ($record) {
                        // Hanya tampilkan jika user sudah terverifikasi dan bukan diri sendiri
                        return $record->verified_at && Auth::id() !== $record->id;
                    }),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('verifikasi')
                        ->label('Verifikasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Verifikasi User')
                        ->modalDescription('Apakah Anda yakin ingin memverifikasi user ini?')
                        ->modalSubmitActionLabel('Ya, Verifikasi')
                        ->action(function ($record) {
                            $record->update(['verified_at' => now()]);
                            Notification::make()
                                ->title('User berhasil diverifikasi')
                                ->success()
                                ->send();
                        })
                        ->visible(fn ($record) => !$record->verified_at),

                    Tables\Actions\Action::make('batalkan_verifikasi')
                        ->label('Batalkan Verifikasi')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Batalkan Verifikasi User')
                        ->modalDescription('Apakah Anda yakin ingin membatalkan verifikasi user ini?')
                        ->modalSubmitActionLabel('Ya, Batalkan')
                        ->action(function ($record) {
                            $record->update(['verified_at' => null]);
                            Notification::make()
                                ->title('Verifikasi user berhasil dibatalkan')
                                ->success()
                                ->send();
                        })
                        ->visible(fn ($record) => $record->verified_at),

                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->filters([
                Tables\Filters\Filter::make('verified')
                    ->label('Terverifikasi')
                    ->query(fn ($query) => $query->whereNotNull('verified_at')),
                Tables\Filters\Filter::make('unverified')
                    ->label('Belum Terverifikasi')
                    ->query(fn ($query) => $query->whereNull('verified_at')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}