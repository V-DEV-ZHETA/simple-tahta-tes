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
                Forms\Components\TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->autocomplete()
                    ->autofocus(),

                Forms\Components\Placeholder::make('verification_error')
                    ->content(fn () => $this->verificationError)
                    ->visible(fn () => !is_null($this->verificationError))
                    ->extraAttributes(['class' => 'text-red-500 text-sm']),

                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(),

                Forms\Components\Select::make('tahun')
                    ->label('Tahun')
                    ->options($this->getTahunOptions())
                    ->default(date('Y'))
                    ->required()
                    ->native(false)
                    ->searchable(),

                Forms\Components\Checkbox::make('remember')
                    ->label('Ingat Saya'),
            ]);
    }

    protected function getTahunOptions(): array
    {
        $currentYear = date('Y');
        $years = [];
        
        // Generate tahun dari 5 tahun lalu sampai 5 tahun kedepan
        for ($year = $currentYear - 0; $year <= $currentYear + 1; $year++) {
            $years[$year] = $year;
        }
        
        return $years;
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        // Simpan tahun ke session untuk digunakan di aplikasi
        session(['selected_year' => $data['tahun']]);
        
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
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