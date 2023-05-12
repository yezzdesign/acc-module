<?php

namespace Modules\Acc\Filament\Resources;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Modules\Acc\Entities\User;
use Modules\Acc\Filament\Resources\UserResource\Pages\CreateUser;
use Modules\Acc\Filament\Resources\UserResource\Pages\EditUser;
use Modules\Acc\Filament\Resources\UserResource\Pages\ListUsers;
use Modules\Acc\Filament\Resources\UserResource\RelationManagers\LinksRelationManager;
use Modules\Acc\Filament\Resources\UserResource\RelationManagers\PostsRelationManager;
use Modules\Blog\Entities\Post;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class UserResource extends Resource
{
    use SoftDeletes;

    protected static ?string $model = User::class;

    protected static ?string $slug = 'authors';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        //dd(PostResource::getPages());
        return $form
            ->columns(4)
            ->schema([
                Forms\Components\Tabs::make('Heading')
                    ->columnSpan(3)
                    ->tabs([

                        // Tab 1 important informations
                        Forms\Components\Tabs\Tab::make( __('acc::index.form.tab1.title') )
                        ->icon('heroicon-o-bell')
                        ->columns(4)
                        ->schema(
                            [
                                Forms\Components\TextInput::make('name')
                                    ->hint( __('acc::index.form.tab1.name.hint') )
                                    ->label( __('acc::index.form.tab1.name.label') )
                                    ->columnSpan(2)
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('email')
                                    ->columnSpan(2)
                                    ->hint( __('acc::index.form.tab1.email.hint') )
                                    ->label( __('acc::index.form.tab1.email.label') )
                                    ->email()
                                    ->required()
                                    ->maxLength(255),

                                TinyEditor::make('biography')
                                    ->columnSpan(4)
                                    ->hint( __('acc::index.form.tab1.biographie.hint') )
                                    ->label( __('acc::index.form.tab1.biographie.label') )

                            ],
                        ),

                        // Tab 2 Pictures
                        Forms\Components\Tabs\Tab::make(__('acc::index.form.tab2.title'))
                            ->icon('heroicon-o-identification')
                            ->columns(6)
                            ->schema([

                                Forms\Components\SpatieMediaLibraryFileUpload::make(__('acc::index.form.tab2.image.name'))
                                    ->image()
                                    ->collection('profile_image')
                                    ->conversion('full')
                                    ->columnSpan(6),

                            ]),

                        // Tab 3 others
                        Forms\Components\Tabs\Tab::make( __('acc::index.form.tab3.title') )
                        ->columns(6)
                        ->schema([
                            Forms\Components\DateTimePicker::make('email_verified_at'),

                            Forms\Components\Textarea::make('two_factor_secret')
                                ->maxLength(65535),

                            Forms\Components\Textarea::make('two_factor_recovery_codes')
                                ->maxLength(65535),

                            Forms\Components\DateTimePicker::make('two_factor_confirmed_at'),

                            Forms\Components\TextInput::make('current_team_id'),
                        ])
                    ]),

                    // Informations
                    Forms\Components\Card::make()
                        ->schema([

                                    // Created at
                                    Forms\Components\Placeholder::make('created_at')
                                        ->label( __('acc::index.form.information.created_at') )
                                        ->content(fn (?User $record): ?string => $record->created_at?->diffForHumans()),

                                    // Last updated at
                                    Forms\Components\Placeholder::make('updated_at')
                                        ->label( __('acc::index.form.information.updated_at') )
                                        ->content(fn (?User $record): ?string => $record->updated_at?->diffForHumans()),

                                    // All posts counted
                                    Forms\Components\Placeholder::make('Test')
                                        ->label(__('acc::index.form.information.post_count'))
                                        ->content( fn (?User $record): ?string => Post::where('author_id', '=',  $record->id)->count() )

                        ])
                        ->columnSpan(1)
                        ->hidden(fn (?User $record) => $record === null),
                ]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label( __('acc::index.table.id') )
                    ->searchable()
                    ->sortable(),

                Tables\Columns\SpatieMediaLibraryImageColumn::make(__('acc::index.table.profile'))
                    ->collection('profile_image')
                    ->conversion('thumb')
                    ->width(60)
                    ->height(60),

                Tables\Columns\TextColumn::make('name')
                    ->label( __('acc::index.table.name') )
                    ->description( fn( User $record ):string => $record->email )
                    ->tooltip( fn( User $record ):string => $record->name )
                    ->searchable(['name', 'email'])
                    ->sortable()
                    ->limit(15),

                IconColumn::make('email_verified_at')
                    ->label( __('acc::index.table.verification') )
                    ->sortable()
                    ->boolean()
                    ->default(false)
                    ->trueIcon('heroicon-o-badge-check')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label( __('acc::index.table.created_at') )
                    ->dateTime('j M, Y')
                    ->searchable()
                    ->sortable()
                    ->tooltip( fn( User $record ):string => $record->name )

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LinksRelationManager::class,
            PostsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static function getNavigationLabel(): string
    {
        return __('acc::index.filament.label.user_settings');
    }

    protected static function getNavigationGroup(): string
    {
        return __('acc::index.filament.title.user_settings');
    }

    public static function getModelLabel(): string
    {
        return __('acc::index.filament.label.user_settings');
    }

    public static function getPluralModelLabel(): string
    {
        return __('acc::index.filament.label.user_settings');
    }


}
