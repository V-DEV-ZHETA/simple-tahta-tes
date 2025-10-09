<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use App\Models\Instansi;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Notifications\Notification;
use Illuminate\Auth\Events\Registered;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;

class Register extends BaseRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required(),
                        Select::make('instansi_id')
                            ->label('Instansi')
                            ->options(Instansi::pluck('name', 'id'))
                            ->required(),
                        TextInput::make('satuan_kerja')
                            ->label('Satuan Kerja')
                            ->required(),
                        TextInput::make('username')
                            ->label('Username')
                            ->required()
                            ->rules(['unique:users,username']),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->rules(['unique:users,email']),
                        TextInput::make('telepon')
                            ->label('Nomor Telepon/ Wa Aktif')
                            ->required(),
                        TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password()
                            ->required(),
                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Kata Sandi')
                            ->password()
                            ->required()
                            ->same('password'),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    public function register(): ?RegistrationResponse
    {
        $data = $this->form->getState();
        
        $data['password'] = Hash::make($data['password']);
        
        unset($data['password_confirmation']);

        $user = User::create($data);
        
        try {
            $user->assignRole('User');
            $user->assignRole('Pelaksana');
        } catch (\Exception $e) {
        }

        event(new Registered($user));
        
        Auth::guard(Filament::getAuthGuard())->logout();
        session()->invalidate();
        session()->regenerateToken();

        Notification::make()
            ->success()
            ->title('Registrasi Berhasil')
            ->body('Akun Anda berhasil dibuat. Silakan login dengan username dan password Anda.')
            ->send();

        $this->redirect(Filament::getLoginUrl());
        
        return null;
    }

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Daftar');
    }
}