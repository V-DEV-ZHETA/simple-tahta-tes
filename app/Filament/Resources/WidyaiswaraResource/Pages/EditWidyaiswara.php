<?php

namespace App\Filament\Resources\WidyaiswaraResource\Pages;

use App\Filament\Resources\WidyaiswaraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWidyaiswara extends EditRecord
{
    protected static string $resource = WidyaiswaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
