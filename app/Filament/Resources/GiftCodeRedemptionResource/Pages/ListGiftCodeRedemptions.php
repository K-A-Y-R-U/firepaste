<?php

namespace App\Filament\Resources\GiftCodeRedemptionResource\Pages;

use App\Filament\Resources\GiftCodeRedemptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGiftCodeRedemptions extends ListRecords
{
    protected static string $resource = GiftCodeRedemptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
