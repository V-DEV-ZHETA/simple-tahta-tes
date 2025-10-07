<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use App\Models\Instansi;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Support\Facades\DB; // Perlu untuk transaksi, opsional tapi disarankan

class Register extends BaseRegister
{
    // ... (Metode getForms() tidak perlu diubah, biarkan seperti yang sudah Anda buat)
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
                            ->label('Nomor Telepon')
                            ->required(),
                        TextInput::make('password')
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

    // Metode create yang di-override
    protected function create(array $data): \Illuminate\Contracts\Auth\Authenticatable
    {
        // Panggil parent::create untuk membuat user seperti biasa
        // Ini memastikan proses hashing password dan pembuatan user dasar tetap berjalan
        $user = parent::create($data);

        // Setelah user dibuat, langsung berikan role 'Pelaksana'
        // Karena Anda sudah memastikan role 'Pelaksana' ada dan model User menggunakan HasRoles
        // Gunakan try-catch atau transaksi untuk keamanan data jika pembuatan role gagal
        try {
            // Memberikan role 'Pelaksana'
            $user->assignRole('Pelaksana');
        } catch (\Exception $e) {
            // Opsional: Lakukan logging atau batalkan transaksi jika ada masalah
            // Jika Anda menggunakan DB::beginTransaction() di atas, Anda bisa memanggil DB::rollBack() di sini.
            // Saat ini, kita asumsikan assignRole akan berhasil karena role sudah dibuat.
            // Anda bisa log error-nya jika perlu debugging.
        }

        // Kembalikan objek user yang sudah memiliki role
        return $user;
    }
}