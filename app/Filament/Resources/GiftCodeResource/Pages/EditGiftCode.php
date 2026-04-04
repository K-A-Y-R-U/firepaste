<?php

namespace App\Filament\Resources\GiftCodeResource\Pages;

use App\Filament\Resources\GiftCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGiftCode extends EditRecord
{
    protected static string $resource = GiftCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}