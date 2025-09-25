<?php

namespace App\Filament\Resources\BangkomResource\Pages;

use App\Filament\Resources\BangkomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBangkom extends EditRecord
{
    protected static string $resource = BangkomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
