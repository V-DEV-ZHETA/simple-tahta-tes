<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum JalurPelatihan: string implements HasLabel
{
    case KLASIKAL = 'Klasikal';
    case NON_KLASIKAL = 'Non Klasikal';

    public function getLabel(): ?string  
    {
        return match ($this) {
            self::KLASIKAL => 'Klasikal',
            self::NON_KLASIKAL => 'Non Klasikal',
        };
    }
}