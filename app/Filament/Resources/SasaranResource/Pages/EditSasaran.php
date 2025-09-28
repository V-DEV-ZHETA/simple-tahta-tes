<?php

namespace App\Filament\Resources\SasaranResource\Pages;

use App\Filament\Resources\SasaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSasaran extends EditRecord
{
    protected static string $resource = SasaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
