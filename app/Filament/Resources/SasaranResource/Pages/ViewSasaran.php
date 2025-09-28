<?php

namespace App\Filament\Resources\SasaranResource\Pages;

use App\Filament\Resources\SasaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSasaran extends ViewRecord
{
    protected static string $resource = SasaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
