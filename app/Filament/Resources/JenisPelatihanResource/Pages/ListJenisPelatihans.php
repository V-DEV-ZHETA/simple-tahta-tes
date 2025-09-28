<?php

namespace App\Filament\Resources\JenisPelatihanResource\Pages;

use App\Filament\Resources\JenisPelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisPelatihans extends ListRecords
{
    protected static string $resource = JenisPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
