<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\Catalog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use FilamentTiptapEditor\TiptapEditor;
use FilamentTiptapEditor\Enums\TiptapOutput;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Posts';

    protected static ?string $modelLabel = 'Post';

    protected static ?string $pluralModelLabel = 'Posts';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Post')
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('catalog_id')
                            ->label('Catálogo')
                            ->relationship('catalog', 'nombre')
                            ->searchable()
                            ->preload()
                            ->placeholder('Selecciona un catálogo')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nombre')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $context, $state, Forms\Set $set) => 
                                        $context === 'create' ? $set('slug', Str::slug($state)) : null
                                    ),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('descripcion')
                                    ->rows(2),
                                Forms\Components\Toggle::make('activo')
                                    ->default(true),
                            ])
                            ->createOptionUsing(function (array $data) {
                                if (empty($data['slug'])) {
                                    $data['slug'] = Str::slug($data['nombre']);
                                }
                                return Catalog::create($data)->getKey();
                            }),

                        Forms\Components\TextInput::make('pestana')
                            ->label('Pestaña')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Contenido')
                    ->schema([
                        TiptapEditor::make('contenido')
                            ->label('Contenido')
                            ->columnSpanFull()
                            ->profile('default')
                            ->output(TiptapOutput::Html)
                            ->tools([
                                // Herramientas básicas de formato
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'subscript',
                                'superscript',
                                'small',
                                'lead',
                                
                                // Colores
                                'color',
                                'highlight',
                                
                                // Encabezados
                                'heading',
                                
                                // Listas
                                'bullet-list',
                                'ordered-list',
                                'checked-list',
                                
                                // Elementos de bloque
                                'blockquote',
                                'hr',
                                
                                // Alineación
                                'align-left',
                                'align-center',
                                'align-right',
                                
                                // Enlaces y medios
                                'link',
                                'media',
                                'oembed',
                                
                                // Código
                                'code',
                                'code-block',
                                
                                // Tablas y layouts
                                'table',
                                'grid-builder',
                                
                                // Funcionalidades adicionales
                                'details',
                                'source',
                                
                                // Historial
                                'redo',
                                'undo',
                            ])
                            ->maxContentWidth('full')
                            ->extraInputAttributes([
                                'style' => 'min-height: 600px; height: 600px;'
                            ])
                            ->directory('uploads/posts') // Directorio para subir archivos
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                            ->maxFileSize(5120) // 5MB
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('catalog.nombre')
                    ->label('Catálogo')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->placeholder('Sin catálogo'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('catalog_id')
                    ->label('Catálogo')
                    ->relationship('catalog', 'nombre')
                    ->preload()
                    ->placeholder('Todos los catálogos'),

                Tables\Filters\Filter::make('sin_catalogo')
                    ->label('Sin catálogo')
                    ->query(fn ($query) => $query->whereNull('catalog_id'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('asignar_catalogo')
                        ->label('Asignar catálogo')
                        ->icon('heroicon-o-folder')
                        ->form([
                            Forms\Components\Select::make('catalog_id')
                                ->label('Catálogo')
                                ->options(Catalog::active()->pluck('nombre', 'id'))
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            $records->each->update(['catalog_id' => $data['catalog_id']]);
                        }),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}