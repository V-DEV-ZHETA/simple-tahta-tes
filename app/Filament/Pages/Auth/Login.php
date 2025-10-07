<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;

class Login extends BaseLogin
{
    public ?string $verificationError = null;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->label(__('filament-panels::pages/auth/login.form.email.label'))
                    ->email()
                    ->required()
                    ->autocomplete()
                    ->autofocus(),

                Forms\Components\Placeholder::make('verification_error')
                    ->content(fn () => $this->verificationError)
                    ->visible(fn () => !is_null($this->verificationError))
                    ->extraAttributes(['class' => 'text-red-500 text-sm']),

                Forms\Components\TextInput::make('password')
                    ->label(__('filament-panels::pages/auth/login.form.password.label'))
                    ->password()
                    ->required(),
            ]);
    }

    public function authenticate(): ?\Filament\Http\Responses\Auth\Contracts\LoginResponse
    {
        $response = parent::authenticate();

        if (Auth::check() && !Auth::user()->isVerified()) {
            Auth::logout();
            $this->verificationError = 'Akun anda belum diverifikasi, silahkan hubungi admin';
            return null;
        }

        return $response;
    }
}
