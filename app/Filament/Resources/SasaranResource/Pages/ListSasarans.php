<?php

namespace App\Filament\Resources\SasaranResource\Pages;

use App\Filament\Resources\SasaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSasarans extends ListRecords
{
    protected static string $resource = SasaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
