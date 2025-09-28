<?php

namespace App\Filament\Resources\JenisPelatihanResource\Pages;

use App\Filament\Resources\JenisPelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewJenisPelatihan extends ViewRecord
{
    protected static string $resource = JenisPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
