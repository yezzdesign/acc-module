<?php

namespace Modules\Acc\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Modules\Blog\Entities\Post;
use Modules\Blog\Filament\Resources\PostResource;

class PostsRelationManager extends RelationManager
{

    protected static string $relationship = 'posts';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('author_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('blog::index.table.id'))
                    ->sortable(),

                IconColumn::make('launch_date')
                    ->tooltip( (fn(Post $record):string => ($record->launch_date)? \Carbon\Carbon::parse($record->launch_date,config('app.timezone'))->translatedFormat('d F, Y') : 'n/a') )
                    ->label( __('blog::index.table.launch_date') )
                    ->sortable()
                    ->boolean()
                    ->default(false)
                    ->trueIcon('heroicon-o-badge-check')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\TextColumn::make('cover_image_path')
                    ->label( __('blog::index.table.cover_image_path') ),

                Tables\Columns\TextColumn::make('title')
                    ->label( __('blog::index.table.title'))
                    ->tooltip( fn(Post $record):string => $record->title )
                    ->limit(15)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('content_short')
                    ->label( __('blog::index.table.content_short') )
                    ->tooltip( fn(Post $record):string => $record->content_short )
                    ->searchable()
                    ->sortable()
                    ->limit(15),

                Tables\Columns\TextColumn::make('categories')
                    ->label( __('blog::index.table.categories') )
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->url(PostResource::getUrl('create'))
                    //->url(route('blog-filament.resources.modules/blog/entities/posts.create')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    //->url(fn (Post $record): string => route('blog-filament.resources.modules/blog/entities/posts.edit', $record)),
        //->url(fn (Post $record): \Closure => PostResource::getRoutes('edit', $record)),
                    ->url(fn (Post $record): string => PostResource::getUrl('edit', $record)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

}
