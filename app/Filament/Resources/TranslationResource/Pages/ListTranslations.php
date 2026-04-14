<?php

namespace App\Filament\Resources\TranslationResource\Pages;

use App\Filament\Resources\TranslationResource;
use App\Models\Translation;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListTranslations extends ListRecords
{
    protected static string $resource = TranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Importar desde archivos JSON existentes a la BD
            Actions\Action::make('import_from_files')
                ->label('Importar desde JSON')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Importar traducciones desde archivos JSON')
                ->modalDescription('Esto importará todas las traducciones de los archivos lang/es.json y lang/en.json a la base de datos.')
                ->modalSubmitActionLabel('Importar ahora')
                ->action(function () {
                    $imported = Translation::importFromFiles();
                    Notification::make()
                        ->title("¡Importadas {$imported} traducciones!")
                        ->success()
                        ->send();
                }),

            // Exportar de la BD a archivos JSON
            Actions\Action::make('sync_to_files')
                ->label('Exportar a JSON')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Exportar traducciones a archivos JSON')
                ->modalDescription('Esto actualizará los archivos lang/es.json y lang/en.json con las traducciones de la base de datos.')
                ->modalSubmitActionLabel('Exportar ahora')
                ->action(function () {
                    Translation::syncToFiles();
                    Translation::clearTranslationCache();
                    Notification::make()
                        ->title('¡Traducciones exportadas a JSON!')
                        ->success()
                        ->send();
                }),

            Actions\CreateAction::make()
                ->icon('heroicon-o-plus'),
        ];
    }
}