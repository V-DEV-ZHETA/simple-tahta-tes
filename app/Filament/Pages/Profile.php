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
use Filament\Forms\Components\FileUpload;

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
            'avatar' => $user->avatar ?? null,
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
                Section::make('Avatar')
                    ->description('Upload foto profil Anda')
                    ->schema([
                        FileUpload::make('avatar')
                            ->label('')
                            ->image()
                            ->imageEditor()
                            ->directory('avatars')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
                            ->helperText('Drag & Drop your files or Browse. Max size: 2MB')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->compact(),

                Section::make('General')
                    ->description('Informasi umum akun Anda')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-user')
                                    ->placeholder('Masukkan nama lengkap'),
                                    
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-envelope')
                                    ->placeholder('nama@email.com'),
                                    
                                TextInput::make('username')
                                    ->label('Username')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-at-symbol')
                                    ->placeholder('username'),
                                    
                                TextInput::make('telepon')
                                    ->label('Phone')
                                    ->tel()
                                    ->maxLength(20)
                                    ->prefixIcon('heroicon-o-phone')
                                    ->placeholder('08xxxxxxxxxx'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Password')
                    ->description('Ubah password akun Anda. Leave empty if you don\'t wanna change password')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextInput::make('current_password')
                                    ->label('Old Password')
                                    ->password()
                                    ->revealable()
                                    ->maxLength(255)
                                    ->dehydrated(false)
                                    ->prefixIcon('heroicon-o-lock-closed')
                                    ->placeholder('Masukkan password lama')
                                    ->helperText('Wajib diisi jika ingin mengubah password')
                                    ->requiredWith('password'),
                                    
                                TextInput::make('password')
                                    ->label('New Password')
                                    ->password()
                                    ->revealable()
                                    ->maxLength(255)
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->rule('confirmed')
                                    ->prefixIcon('heroicon-o-key')
                                    ->placeholder('Masukkan password baru')
                                    ->minLength(8)
                                    ->helperText('Minimal 8 karakter'),
                                    
                                TextInput::make('password_confirmation')
                                    ->label('New Password (Confirm)')
                                    ->password()
                                    ->revealable()
                                    ->same('password')
                                    ->visible(fn (Get $get): bool => filled($get('password')))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->prefixIcon('heroicon-o-key')
                                    ->placeholder('Konfirmasi password baru'),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = Auth::user();

        $currentPassword = $data['current_password'] ?? null;
        $newPassword = $data['password'] ?? null;
        $changePassword = filled($currentPassword) || filled($newPassword);

        if ($changePassword) {
            if (!filled($currentPassword)) {
                Notification::make()
                    ->title('Old Password harus diisi')
                    ->body('Silakan masukkan password lama Anda untuk mengubah password.')
                    ->danger()
                    ->duration(5000)
                    ->send();
                return;
            }

            if (!Hash::check($currentPassword, $user->password)) {
                Notification::make()
                    ->title('Old Password salah')
                    ->body('Password lama yang Anda masukkan tidak sesuai.')
                    ->danger()
                    ->duration(5000)
                    ->send();
                return;
            }
        }

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'telepon' => $data['telepon'],
        ];

        if (isset($data['avatar'])) {
            $updateData['avatar'] = $data['avatar'];
        }

        $user->update($updateData);

        if ($changePassword && filled($newPassword)) {
            $user->update([
                'password' => Hash::make($newPassword),
            ]);
        }

        Notification::make()
            ->title('Profil berhasil diperbarui')
            ->body('Data profil Anda telah berhasil disimpan.')
            ->success()
            ->duration(3000)
            ->send();

        $this->mount();
    }
}