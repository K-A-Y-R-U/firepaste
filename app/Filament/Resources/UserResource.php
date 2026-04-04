<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nombre'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Email'),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Email verificado en'),
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Nombre'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label('Email'),
                
                // Columna para mostrar roles
                Tables\Columns\TextColumn::make('current_roles')
                    ->label('Roles')
                    ->badge()
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
                            ->pluck('hexa_roles.name')
                            ->toArray();
                        
                        return !empty($roles) ? implode(', ', $roles) : 'Sin rol';
                    }),
                
                // Indicador VIP
                Tables\Columns\IconColumn::make('is_vip')
                    ->boolean()
                    ->getStateUsing(fn (User $record): bool => $record->isVip())
                    ->label('VIP')
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Email verificado')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Creado en'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Actualizado en'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options(function () {
                        return \DB::table('hexa_roles')
                            ->pluck('name', 'name')
                            ->toArray();
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $role): Builder => $query->whereExists(function ($subQuery) use ($role) {
                                $subQuery->select(\DB::raw(1))
                                    ->from('user_roles')
                                    ->join('hexa_roles', 'user_roles.role_id', '=', 'hexa_roles.id')
                                    ->whereColumn('user_roles.user_id', 'users.id')
                                    ->where('hexa_roles.name', $role);
                            })
                        );
                    })
                    ->label('Filtrar por rol'),
                
                Tables\Filters\Filter::make('vip_only')
                    ->query(fn (Builder $query): Builder => 
                        $query->whereExists(function ($subQuery) {
                            $subQuery->select(\DB::raw(1))
                                ->from('user_roles')
                                ->join('hexa_roles', 'user_roles.role_id', '=', 'hexa_roles.id')
                                ->whereColumn('user_roles.user_id', 'users.id')
                                ->where('hexa_roles.name', 'VIP');
                        })
                    )
                    ->label('Solo usuarios VIP'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                
                // Acción para gestionar roles del usuario
                Action::make('manageRoles')
                    ->label('Gestionar Roles')
                    ->icon('heroicon-o-user-plus')
                    ->color('warning')
                    ->form([
                        Forms\Components\Select::make('role_id')
                            ->label('Asignar Rol')
                            ->options(function () {
                                // Obtener roles desde la base de datos de Hexa
                                return \DB::table('hexa_roles')
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->placeholder('Selecciona un rol')
                            ->helperText('Selecciona el rol que deseas asignar a este usuario'),
                        
                        Forms\Components\Textarea::make('reason')
                            ->label('Motivo del cambio (opcional)')
                            ->placeholder('Ej: Promoción a VIP por suscripción')
                            ->rows(2),
                    ])
                    ->fillForm(function (User $record): array {
                        // Obtener el rol actual del usuario
                        $currentRole = \DB::table('user_roles')
                            ->where('user_id', $record->id)
                            ->first();
                        
                        return [
                            'role_id' => $currentRole->role_id ?? null,
                        ];
                    })
                    ->action(function (User $record, array $data): void {
                        if (isset($data['role_id'])) {
                            // Primero eliminar roles existentes
                            \DB::table('user_roles')
                                ->where('user_id', $record->id)
                                ->delete();
                            
                            // Asignar el nuevo rol
                            \DB::table('user_roles')->insert([
                                'user_id' => $record->id,
                                'role_id' => $data['role_id'],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Gestionar Roles del Usuario')
                    ->modalDescription('Asigna o cambia el rol de este usuario')
                    ->modalSubmitActionLabel('Asignar Rol'),
                
                // Acción rápida para hacer VIP
                Action::make('makeVip')
                    ->label('Hacer VIP')
                    ->icon('heroicon-o-star')
                    ->color('success')
                    ->visible(fn (User $record): bool => !$record->isVip())
                    ->action(function (User $record): void {
                        // Buscar el rol VIP
                        $vipRole = \DB::table('hexa_roles')
                            ->where('name', 'VIP')
                            ->orWhere('name', 'vip')
                            ->first();
                        
                        if ($vipRole) {
                            // Eliminar roles existentes
                            \DB::table('user_roles')
                                ->where('user_id', $record->id)
                                ->delete();
                            
                            // Asignar rol VIP
                            \DB::table('user_roles')->insert([
                                'user_id' => $record->id,
                                'role_id' => $vipRole->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    })
                    ->requiresConfirmation()
                    ->modalDescription('¿Deseas convertir este usuario en VIP?'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    // Acción masiva para asignar rol
                    Tables\Actions\BulkAction::make('assignRole')
                        ->label('Asignar Rol')
                        ->icon('heroicon-o-user-plus')
                        ->color('warning')
                        ->form([
                            Forms\Components\Select::make('role_id')
                                ->label('Rol a asignar')
                                ->options(function () {
                                    return \DB::table('hexa_roles')
                                        ->pluck('name', 'id')
                                        ->toArray();
                                })
                                ->required()
                                ->helperText('Este rol se asignará a todos los usuarios seleccionados'),
                        ])
                        ->action(function ($records, array $data): void {
                            foreach ($records as $record) {
                                // Eliminar roles existentes
                                \DB::table('user_roles')
                                    ->where('user_id', $record->id)
                                    ->delete();
                                
                                // Asignar nuevo rol
                                \DB::table('user_roles')->insert([
                                    'user_id' => $record->id,
                                    'role_id' => $data['role_id'],
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        })
                        ->requiresConfirmation()
                        ->modalDescription('Se asignará el rol seleccionado a todos los usuarios seleccionados'),
                    
                    // Acción masiva para hacer VIP
                    Tables\Actions\BulkAction::make('makeVipBulk')
                        ->label('Hacer VIP')
                        ->icon('heroicon-o-star')
                        ->color('success')
                        ->action(function ($records): void {
                            // Buscar el rol VIP
                            $vipRole = \DB::table('hexa_roles')
                                ->where('name', 'VIP')
                                ->orWhere('name', 'vip')
                                ->first();
                            
                            if ($vipRole) {
                                foreach ($records as $record) {
                                    // Eliminar roles existentes
                                    \DB::table('user_roles')
                                        ->where('user_id', $record->id)
                                        ->delete();
                                    
                                    // Asignar rol VIP
                                    \DB::table('user_roles')->insert([
                                        'user_id' => $record->id,
                                        'role_id' => $vipRole->id,
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ]);
                                }
                            }
                        })
                        ->requiresConfirmation()
                        ->modalDescription('¿Deseas convertir todos los usuarios seleccionados en VIP?'),
                ]),
            ]);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}