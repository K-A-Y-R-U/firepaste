<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranslationResource\Pages;
use App\Models\Translation;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class TranslationResource extends Resource
{
    protected static ?string $model = Translation::class;
    protected static ?string $navigationIcon = 'heroicon-o-language';
    protected static ?string $navigationLabel = 'Traducciones';
    protected static ?string $navigationGroup = 'Setting & Access';
    protected static ?int $navigationSort = 11;
    protected static ?string $modelLabel = 'Traducción';
    protected static ?string $pluralModelLabel = 'Traducciones';

    public static function form(Form $form): Form
    {
        $activeLanguages = Language::active()->get();

        $translationFields = [];
        foreach ($activeLanguages as $language) {
            $translationFields[] = Forms\Components\Section::make()
                ->heading(($language->flag_emoji ? $language->flag_emoji . ' ' : '') . $language->native_name)
                ->description($language->name)
                ->schema([
                    Forms\Components\Textarea::make("translations.{$language->code}")
                        ->label('')
                        ->rows(3)
                        ->placeholder("Traducción en {$language->name}...")
                ])
                ->collapsible()
                ->compact()
                ->columnSpan(1);
        }

        return $form
            ->schema([
                Forms\Components\Section::make('Clave de traducción')
                    ->description('El identificador único que usas en el código con __("clave")')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Clave / Texto original')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('ej: Inicio, Buscar, Membresías...')
                            ->helperText('Usa en tus vistas: {{ __("Inicio") }}')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('group')
                            ->label('Grupo')
                            ->options([
                                'general'    => 'General',
                                'navigation' => 'Navegación',
                                'auth'       => 'Autenticación',
                                'buttons'    => 'Botones',
                                'messages'   => 'Mensajes',
                                'pages'      => 'Páginas',
                                'vip'        => 'VIP',
                                'forms'      => 'Formularios',
                            ])
                            ->default('general')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Traducciones por idioma')
                    ->description('Escribe la traducción para cada idioma activo')
                    ->schema($translationFields)
                    ->columns(count($activeLanguages) >= 2 ? 2 : 1),
            ]);
    }

    public static function table(Table $table): Table
    {
        $activeLanguages = Language::active()->get();

        $columns = [
            Tables\Columns\TextColumn::make('key')
                ->label('Clave')
                ->searchable()
                ->sortable()
                ->limit(40)
                ->weight('bold')
                ->copyable()
                ->copyMessage('¡Clave copiada!'),

            Tables\Columns\TextColumn::make('group')
                ->label('Grupo')
                ->badge()
                ->color(fn (string $state): string => match($state) {
                    'navigation' => 'success',
                    'auth'       => 'warning',
                    'vip'        => 'danger',
                    'buttons'    => 'info',
                    default      => 'gray',
                })
                ->sortable()
                ->searchable(),
        ];

        foreach ($activeLanguages as $language) {
            $columns[] = Tables\Columns\TextColumn::make("translations.{$language->code}")
                ->label(($language->flag_emoji ? $language->flag_emoji . ' ' : '') . strtoupper($language->code))
                ->limit(35)
                ->toggleable()
                ->getStateUsing(fn ($record) => $record->translations[$language->code] ?? '-')
                ->color(fn ($state) => $state === '-' ? 'danger' : 'success');
        }

        $columns[] = Tables\Columns\TextColumn::make('updated_at')
            ->label('Actualizado')
            ->dateTime('d/m/Y')
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);

        return $table
            ->columns($columns)
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->label('Grupo')
                    ->options([
                        'general'    => 'General',
                        'navigation' => 'Navegación',
                        'auth'       => 'Autenticación',
                        'buttons'    => 'Botones',
                        'messages'   => 'Mensajes',
                        'pages'      => 'Páginas',
                        'vip'        => 'VIP',
                        'forms'      => 'Formularios',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('key', 'asc')
            ->striped()
            ->paginated([25, 50, 100]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTranslations::route('/'),
            'create' => Pages\CreateTranslation::route('/create'),
            'edit'   => Pages\EditTranslation::route('/{record}/edit'),
        ];
    }
}