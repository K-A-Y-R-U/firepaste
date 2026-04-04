<?php

namespace App\Filament\Resources\GiftCodeResource\Pages;

use App\Filament\Resources\GiftCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGiftCode extends ViewRecord
{
    protected static string $resource = GiftCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}