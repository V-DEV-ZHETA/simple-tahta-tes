<?php

namespace App\Filament\Resources\BentukPelatihanResource\Pages;

use App\Filament\Resources\BentukPelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBentukPelatihan extends EditRecord
{
    protected static string $resource = BentukPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
