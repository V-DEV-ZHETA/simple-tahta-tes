<?php

namespace App\Filament\Resources\BentukPelatihanResource\Pages;

use App\Filament\Resources\BentukPelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBentukPelatihan extends ViewRecord
{
    protected static string $resource = BentukPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
