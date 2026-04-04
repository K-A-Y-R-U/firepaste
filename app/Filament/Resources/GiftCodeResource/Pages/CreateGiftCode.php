<?php

namespace App\Filament\Resources\GiftCodeResource\Pages;

use App\Filament\Resources\GiftCodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGiftCode extends CreateRecord
{
    protected static string $resource = GiftCodeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}