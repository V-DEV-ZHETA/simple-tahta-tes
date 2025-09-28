<?php

namespace App\Filament\Resources\WidyaiswaraResource\Pages;

use App\Filament\Resources\WidyaiswaraResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWidyaiswara extends ViewRecord
{
    protected static string $resource = WidyaiswaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
