<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GiftCodeResource\Pages;
use App\Models\GiftCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GiftCodeResource extends Resource
{
    protected static ?string $model = GiftCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationLabel = 'Códigos de Regalo';

    protected static ?string $modelLabel = 'Código de Regalo';

    protected static ?string $pluralModelLabel = 'Códigos de Regalo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Código')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true)
                    ->default(fn () => GiftCode::generateUniqueCode())
                    ->helperText('Se genera automáticamente si se deja vacío'),

                Forms\Components\TextInput::make('vip_days')
                    ->label('Días VIP')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(365)
                    ->default(30)
                    ->helperText('Número de días VIP que otorga este código'),

                Forms\Components\TextInput::make('max_uses')
                    ->label('Usos Máximos')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->helperText('Cuántas veces puede ser usado este código'),

                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('Fecha de Expiración')
                    ->nullable()
                    ->helperText('Dejar vacío para que no expire'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true)
                    ->helperText('Solo los códigos activos pueden ser canjeados'),

                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->maxLength(500)
                    ->helperText('Descripción interna del código (no visible para usuarios)'),

                // Campo oculto que se completa automáticamente
                Forms\Components\Hidden::make('created_by')
                    ->default(fn () => auth('admin')->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('vip_days')
                    ->label('Días VIP')
                    ->numeric()
                    ->sortable()
                    ->suffix(' días'),

                Tables\Columns\TextColumn::make('used_count')
                    ->label('Usos')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) => "{$state}/{$record->max_uses}"),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expira')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Nunca'),

                Tables\Columns\TextColumn::make('creator_name')
                    ->label('Creado por')
                    ->searchable(query: function ($query, $search) {
                        return $query->whereExists(function ($subquery) use ($search) {
                            $subquery->select(\DB::raw(1))
                                    ->from('hexa_admins')
                                    ->whereColumn('hexa_admins.id', 'gift_codes.created_by')
                                    ->where('hexa_admins.name', 'like', "%{$search}%");
                        });
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos'),

                Tables\Filters\Filter::make('available')
                    ->label('Disponibles')
                    ->query(fn (Builder $query): Builder => 
                        $query->where('is_active', true)
                              ->where(function ($q) {
                                  $q->whereNull('expires_at')
                                    ->orWhere('expires_at', '>', now());
                              })
                              ->whereRaw('used_count < max_uses')
                    ),

                Tables\Filters\Filter::make('expired')
                    ->label('Expirados')
                    ->query(fn (Builder $query): Builder => 
                        $query->whereNotNull('expires_at')
                              ->where('expires_at', '<=', now())
                    ),

                Tables\Filters\Filter::make('fully_used')
                    ->label('Completamente usados')
                    ->query(fn (Builder $query): Builder => 
                        $query->whereRaw('used_count >= max_uses')
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplicar')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->action(function (GiftCode $record) {
                        $newCode = $record->replicate();
                        $newCode->code = GiftCode::generateUniqueCode();
                        $newCode->used_count = 0;
                        $newCode->created_by = auth('admin')->id();
                        $newCode->save();
                    })
                    ->successNotificationTitle('Código duplicado exitosamente'),
                    
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activar')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->requiresConfirmation(),
                        
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Desactivar')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGiftCodes::route('/'),
            'create' => Pages\CreateGiftCode::route('/create'),
            'view' => Pages\ViewGiftCode::route('/{record}'),
            'edit' => Pages\EditGiftCode::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::available()->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }
}