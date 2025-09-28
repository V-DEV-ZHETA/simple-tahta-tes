<?php

namespace App\Filament\Resources\MentoringResource\Pages;

use App\Filament\Resources\MentoringResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMentoring extends ViewRecord
{
    protected static string $resource = MentoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
