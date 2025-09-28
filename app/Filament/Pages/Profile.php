<?php

namespace App\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get;
use Filament\Forms\Components\Section;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static string $view = 'filament.pages.profile';

    protected static ?string $title = 'Profil';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username ?? '',
            'telepon' => $user->telepon ?? '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('username')
                                    ->label('Username')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('telepon')
                                    ->label('Telepon')
                                    ->tel()
                                    ->maxLength(20),
                            ])
                            ->columns(1),
                    ]),
                Section::make('Password')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextInput::make('current_password')
                                    ->label('Old Password')
                                    ->password()
                                    ->required()
                                    ->maxLength(255)
                                    ->dehydrated(false),
                                TextInput::make('password')
                                    ->label('New Password')
                                    ->password()
                                    ->maxLength(255)
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->rule('confirmed'),
                                TextInput::make('password_confirmation')
                                    ->label('New Password (Confirm)')
                                    ->password()
                                    ->same('password')
                                    ->visible(fn (Get $get): bool => filled($get('password')))
                                    ->dehydrated(fn ($state) => filled($state)),
                            ])
                            ->columns(1),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = Auth::user();

        if (!Hash::check($data['current_password'], $user->password)) {
            Notification::make()
                ->title('Old Password salah')
                ->danger()
                ->send();
            return;
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'telepon' => $data['telepon'],
        ]);

        if (filled($data['password'])) {
            $user->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        Notification::make()
            ->title('Profil berhasil diperbarui')
            ->success()
            ->send();

        $this->form->fill();
    }
}
