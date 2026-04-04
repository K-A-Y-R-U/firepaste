<?php

namespace App\Filament\Resources\GiftCodeResource\Pages;

use App\Filament\Resources\GiftCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGiftCodes extends ListRecords
{
    protected static string $resource = GiftCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
