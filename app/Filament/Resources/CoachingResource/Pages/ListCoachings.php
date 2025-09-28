<?php

namespace App\Filament\Resources\CoachingResource\Pages;

use App\Filament\Resources\CoachingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoachings extends ListRecords
{
    protected static string $resource = CoachingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
