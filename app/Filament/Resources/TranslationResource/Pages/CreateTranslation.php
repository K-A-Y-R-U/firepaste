<?php

namespace App\Filament\Resources\TranslationResource\Pages;

use App\Filament\Resources\TranslationResource;
use App\Models\Translation;
use Filament\Resources\Pages\CreateRecord;

class CreateTranslation extends CreateRecord
{
    protected static string $resource = TranslationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        // Exportar automáticamente a JSON al crear una traducción
        Translation::syncToFiles();
        Translation::clearTranslationCache();
    }
}