<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->label('Nombre'),
            Forms\Components\TextInput::make('email')->email()->required()->label('Email'),
            Forms\Components\DateTimePicker::make('email_verified_at')->label('Email verificado en'),
            Forms\Components\TextInput::make('password')
                ->password()
                ->required(fn (string $context): bool => $context === 'create')
                ->dehydrated(fn (?string $state): bool => filled($state))
                ->label('Contraseña'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->label('Nombre'),
                Tables\Columns\TextColumn::make('email')->searchable()->label('Email'),
                Tables\Columns\TextColumn::make('current_roles')
                    ->label('Roles')->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'admin', 'administrator' => 'danger',
                        'vip' => 'warning',
                        'moderator' => 'info',
                        default => 'gray',
                    })
                    ->getStateUsing(function (User $record): string {
                        $roles = \DB::table('user_roles')
                            ->join('hexa_roles', 'user_roles.role_id', '=', 'hexa_roles.id')
                            ->where('user_roles.user_id', $record->id)
                            ->pluck('hexa_roles.name')->toArray();
                        return !empty($roles) ? implode(', ', $roles) : 'Sin rol';
                    }),
                Tables\Columns\IconColumn::make('is_vip')
                    ->boolean()
                    ->getStateUsing(fn (User $record): bool => $record->isVip())
                    ->label('VIP'),
                Tables\Columns\TextColumn::make('vip_expires_at')
                    ->dateTime('d/m/Y H:i')->label('VIP expira')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true)->label('Creado en'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options(fn () => \DB::table('hexa_roles')->pluck('name', 'name')->toArray())
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['value'], fn ($q, $role) => $q->whereExists(function ($sub) use ($role) {
                            $sub->select(\DB::raw(1))->from('user_roles')
                                ->join('hexa_roles', 'user_roles.role_id', '=', 'hexa_roles.id')
                                ->whereColumn('user_roles.user_id', 'users.id')
                                ->where('hexa_roles.name', $role);
                        }));
                    })->label('Filtrar por rol'),

                Tables\Filters\Filter::make('vip_only')
                    ->query(fn (Builder $q): Builder => $q->where('is_vip_active', true)->where('vip_expires_at', '>', now()))
                    ->label('Solo usuarios VIP'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('manageRoles')
                    ->label('Gestionar Roles')->icon('heroicon-o-user-plus')->color('warning')
                    ->form([
                        Forms\Components\Select::make('role_id')->label('Asignar Rol')
                            ->options(fn () => \DB::table('hexa_roles')->pluck('name', 'id')->toArray())
                            ->placeholder('Selecciona un rol'),
                        Forms\Components\Textarea::make('reason')->label('Motivo (opcional)')->rows(2),
                    ])
                    ->fillForm(function (User $record): array {
                        $currentRole = \DB::table('user_roles')->where('user_id', $record->id)->first();
                        return ['role_id' => $currentRole->role_id ?? null];
                    })
                    ->action(function (User $record, array $data): void {
                        if (isset($data['role_id'])) {
                            \DB::table('user_roles')->where('user_id', $record->id)->delete();
                            \DB::table('user_roles')->insert([
                                'user_id' => $record->id, 'role_id' => $data['role_id'],
                                'created_at' => now(), 'updated_at' => now(),
                            ]);
                        }
                    })
                    ->requiresConfirmation()->modalHeading('Gestionar Roles'),

                // ✅ CORREGIDO: ahora activa vip_expires_at correctamente
                Action::make('makeVip')
                    ->label('Hacer VIP')->icon('heroicon-o-star')->color('success')
                    ->visible(fn (User $record): bool => !$record->isVip())
                    ->form([
                        Forms\Components\TextInput::make('days')->label('Días de VIP')
                            ->numeric()->required()->default(30)->minValue(1),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->activateVip((int) $data['days']);

                        $vipRole = \DB::table('hexa_roles')->whereRaw('LOWER(name) = ?', ['vip'])->first();
                        if ($vipRole) {
                            \DB::table('user_roles')->where('user_id', $record->id)->delete();
                            \DB::table('user_roles')->insert([
                                'user_id' => $record->id, 'role_id' => $vipRole->id,
                                'created_at' => now(), 'updated_at' => now(),
                            ]);
                        }
                    })
                    ->requiresConfirmation()->modalHeading('Activar VIP')
                    ->modalDescription('Ingresa los días de VIP a asignar.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('assignRole')
                        ->label('Asignar Rol')->icon('heroicon-o-user-plus')->color('warning')
                        ->form([
                            Forms\Components\Select::make('role_id')->label('Rol')
                                ->options(fn () => \DB::table('hexa_roles')->pluck('name', 'id')->toArray())->required(),
                        ])
                        ->action(function ($records, array $data): void {
                            foreach ($records as $record) {
                                \DB::table('user_roles')->where('user_id', $record->id)->delete();
                                \DB::table('user_roles')->insert([
                                    'user_id' => $record->id, 'role_id' => $data['role_id'],
                                    'created_at' => now(), 'updated_at' => now(),
                                ]);
                            }
                        })->requiresConfirmation(),

                    // ✅ CORREGIDO: bulk makeVip también activa vip_expires_at
                    Tables\Actions\BulkAction::make('makeVipBulk')
                        ->label('Hacer VIP')->icon('heroicon-o-star')->color('success')
                        ->form([
                            Forms\Components\TextInput::make('days')->label('Días de VIP')
                                ->numeric()->required()->default(30)->minValue(1),
                        ])
                        ->action(function ($records, array $data): void {
                            $vipRole = \DB::table('hexa_roles')->whereRaw('LOWER(name) = ?', ['vip'])->first();
                            foreach ($records as $record) {
                                $record->activateVip((int) $data['days']);
                                if ($vipRole) {
                                    \DB::table('user_roles')->where('user_id', $record->id)->delete();
                                    \DB::table('user_roles')->insert([
                                        'user_id' => $record->id, 'role_id' => $vipRole->id,
                                        'created_at' => now(), 'updated_at' => now(),
                                    ]);
                                }
                            }
                        })->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}