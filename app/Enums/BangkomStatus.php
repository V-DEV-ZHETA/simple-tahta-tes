<?php

namespace App\Enums;

enum BangkomStatus: string
{
    case Draft = 'Draft';
    case MenungguVerifikasi = 'Menunggu Verifikasi I';
    case Pengelolaan = 'Pengelolaan';
    case MenungguVerifikasiII = 'Menunggu Verifikasi II';
    case TerbitSTTP = 'Terbit STTP';

    public function getColor(): string
    {
        return match($this) {
            self::Draft => 'gray',
            self::MenungguVerifikasi => 'primary',
            self::Pengelolaan => 'warning',
            self::MenungguVerifikasiII => 'primary',
            self::TerbitSTTP => 'success',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::Draft => '',
            self::MenungguVerifikasi => 'heroicon-o-clock',
            self::Pengelolaan => 'heroicon-o-cog',
            self::MenungguVerifikasiII => 'heroicon-o-clock',
            self::TerbitSTTP => 'heroicon-o-document-check',
        };
    }

    public function getLabel(): string
    {
        return $this->value;
    }
}
