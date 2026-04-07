<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostReportResource\Pages;
use App\Models\PostReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostReportResource extends Resource
{
    protected static ?string $model = PostReport::class;
    protected static ?string $navigationIcon  = 'heroicon-o-flag';
    protected static ?string $navigationLabel = 'Reportes';
    protected static ?string $navigationGroup = 'Moderación';
    protected static ?int    $navigationSort  = 1;

    // ✅ Badge con número de reportes pendientes
    public static function getNavigationBadge(): ?string
    {
        $count = PostReport::where('status', 'pendiente')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('status')
                ->label('Estado')
                ->options(PostReport::STATUSES)
                ->required(),

            Forms\Components\Textarea::make('description')
                ->label('Descripción del usuario')
                ->disabled()
                ->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('post.titulo')
                    ->label('Post')
                    ->limit(40)
                    ->searchable()
                    ->url(fn ($record) => route('posts.show', $record->post_id))
                    ->openUrlInNewTab(),

                Tables\Columns\BadgeColumn::make('reason')
                    ->label('Motivo')
                    ->formatStateUsing(fn ($state) => PostReport::REASONS[$state] ?? $state)
                    ->colors([
                        'danger'  => 'link_caido',
                        'warning' => 'contenido_incorrecto',
                        'gray'    => 'otro',
                    ]),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->placeholder('Sin descripción'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn ($state) => PostReport::STATUSES[$state] ?? $state)
                    ->colors([
                        'danger'  => 'pendiente',
                        'warning' => 'revisado',
                        'success' => 'resuelto',
                    ]),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->placeholder('Anónimo'),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(PostReport::STATUSES),

                Tables\Filters\SelectFilter::make('reason')
                    ->label('Motivo')
                    ->options(PostReport::REASONS),
            ])
            ->actions([
                Tables\Actions\Action::make('marcar_revisado')
                    ->label('Marcar revisado')
                    ->icon('heroicon-o-eye')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === 'pendiente')
                    ->action(fn ($record) => $record->update(['status' => 'revisado'])),

                Tables\Actions\Action::make('marcar_resuelto')
                    ->label('Resolver')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== 'resuelto')
                    ->action(fn ($record) => $record->update(['status' => 'resuelto'])),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPostReports::route('/'),
        ];
    }
}