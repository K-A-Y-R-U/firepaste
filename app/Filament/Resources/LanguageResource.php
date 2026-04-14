<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LanguageResource\Pages;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LanguageResource extends Resource
{
    protected static ?string $model = Language::class;
    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationLabel = 'Idiomas';
    protected static ?string $navigationGroup = 'Setting & Access';
    protected static ?int $navigationSort = 10;
    protected static ?string $modelLabel = 'Idioma';
    protected static ?string $pluralModelLabel = 'Idiomas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Idioma')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Código')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(5)
                            ->placeholder('es, en, fr...')
                            ->helperText('Código ISO 639-1 (2 letras)'),

                        Forms\Components\TextInput::make('name')
                            ->label('Nombre en inglés')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Spanish, English, French...'),

                        Forms\Components\TextInput::make('native_name')
                            ->label('Nombre nativo')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Español, English, Français...'),

                        Forms\Components\TextInput::make('flag_emoji')
                            ->label('Emoji de bandera')
                            ->maxLength(10)
                            ->placeholder('🇪🇸')
                            ->helperText('Emoji opcional para identificación visual'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Configuración')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true)
                            ->helperText('Los idiomas inactivos no estarán disponibles en el frontend'),

                        Forms\Components\Toggle::make('is_default')
                            ->label('Idioma por defecto')
                            ->helperText('Solo un idioma puede ser el predeterminado')
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                if ($state) {
                                    Language::where('is_default', true)->update(['is_default' => false]);
                                }
                            }),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0)
                            ->helperText('Números menores aparecen primero'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('flag_emoji')
                    ->label('Bandera')
                    ->sortable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Código')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('native_name')
                    ->label('Nombre nativo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_default')
                    ->label('Por defecto')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Language $record) {
                        // No permitir borrar el idioma por defecto
                        if ($record->is_default) {
                            throw new \Exception('No puedes eliminar el idioma por defecto.');
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLanguages::route('/'),
            'create' => Pages\CreateLanguage::route('/create'),
            'edit'   => Pages\EditLanguage::route('/{record}/edit'),
        ];
    }
}