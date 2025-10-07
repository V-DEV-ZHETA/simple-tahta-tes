<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use App\Models\Instansi;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Support\Facades\DB;

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

    protected function create(array $data): \Illuminate\Contracts\Auth\Authenticatable
    {
        $user = parent::create($data);

        try {
            $user->assignRole('Pelaksana');
        } catch (\Exception $e) {
        }
        return $user;
    }

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Daftar');
    }
}
