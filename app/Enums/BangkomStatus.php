<?php

namespace App\Enums;

enum BangkomStatus: string
{
    case Pengelolaan = 'pengelolaan';
    case Selesai = 'selesai';
    case Dibatalkan = 'dibatalkan';
    // Add other statuses as needed
}
