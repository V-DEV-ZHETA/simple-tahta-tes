<?php

namespace App\Filament\Resources\JenisPelatihanResource\Pages;

use App\Filament\Resources\JenisPelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisPelatihan extends EditRecord
{
    protected static string $resource = JenisPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
