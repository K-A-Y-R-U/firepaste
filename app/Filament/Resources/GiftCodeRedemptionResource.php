<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GiftCodeRedemptionResource\Pages;
use App\Filament\Resources\GiftCodeRedemptionResource\RelationManagers;
use App\Models\GiftCodeRedemption;
use App\Models\GiftCode;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GiftCodeRedemptionResource extends Resource
{
    protected static ?string $model = GiftCodeRedemption::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Canjes Realizados';

    protected static ?string $modelLabel = 'Canje Realizado';

    protected static ?string $pluralModelLabel = 'Canjes Realizados';

    public static function form(Form $form): Form
    {
        // Los canjes no se crean desde el admin - se generan automáticamente
        return $form
            ->schema([
                Forms\Components\Placeholder::make('info')
                    ->label('')
                    ->content('Los canjes se crean automáticamente cuando los usuarios redimen códigos. No es posible crearlos manualmente.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('giftCode.code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('giftCode.vip_days')
                    ->label('Días VIP')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('redeemed_at')
                    ->label('Fecha de Canje')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('vip_starts_at')
                    ->label('VIP Inicia')
                    ->dateTime()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('vip_ends_at')
                    ->label('VIP Termina')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_vip_active')
                    ->label('VIP Activo')
                    ->boolean()
                    ->getStateUsing(fn (GiftCodeRedemption $record): bool => $record->vip_ends_at->isFuture()),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gift_code_id')
                    ->label('Código de Regalo')
                    ->relationship('giftCode', 'code')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('vip_active')
                    ->label('VIP Activo')
                    ->query(fn (Builder $query): Builder => $query->where('vip_ends_at', '>', now())),

                Tables\Filters\Filter::make('vip_expired')
                    ->label('VIP Expirado')
                    ->query(fn (Builder $query): Builder => $query->where('vip_ends_at', '<=', now())),

                Tables\Filters\Filter::make('redeemed_today')
                    ->label('Canjeados Hoy')
                    ->query(fn (Builder $query): Builder => $query->whereDate('redeemed_at', today())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // No permitir editar ni eliminar canjes
            ])
            ->bulkActions([
                // No permitir acciones masivas
            ])
            ->defaultSort('redeemed_at', 'desc');
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
            'index' => Pages\ListGiftCodeRedemptions::route('/'),
            
            // No incluir create ni edit
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Desactivar la creación desde el admin
    }

    public static function canEdit($record): bool
    {
        return false; // Desactivar la edición desde el admin
    }

    public static function canDelete($record): bool
    {
        return false; // Desactivar la eliminación desde el admin
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('vip_ends_at', '>', now())->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }
}