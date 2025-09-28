<?php

namespace App\Filament\Resources\WidyaiswaraResource\Pages;

use App\Filament\Resources\WidyaiswaraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWidyaiswaras extends ListRecords
{
    protected static string $resource = WidyaiswaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
