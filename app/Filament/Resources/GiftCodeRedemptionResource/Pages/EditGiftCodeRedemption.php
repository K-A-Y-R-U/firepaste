<?php

namespace App\Filament\Resources\GiftCodeRedemptionResource\Pages;

use App\Filament\Resources\GiftCodeRedemptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGiftCodeRedemption extends EditRecord
{
    protected static string $resource = GiftCodeRedemptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
