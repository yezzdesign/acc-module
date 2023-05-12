<?php

namespace Modules\Acc\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Model;
use Modules\Acc\Entities\Setting;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $slug  =   'website-settings';

    protected static ?string $navigationIcon = 'heroicon-o-chip';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('acc::index.settings.form.section1.heading'))
                    ->description(__('acc::index.settings.form.section1.description'))
                    ->compact()
                    ->aside()
                    ->schema([
                        TextInput::make('app_name')
                            ->label( __('acc::index.settings.form.section1.app_name.title') )
                            ->hintIcon('heroicon-o-question-mark-circle')
                            ->hint( __('acc::index.settings.form.section1.app_name.helper_text') ),
                        TextInput::make('app_name_backend')
                            ->hintIcon('heroicon-o-question-mark-circle')
                            ->label( __('acc::index.settings.form.section1.app_name_backend.title') )
                            ->hint( __('acc::index.settings.form.section1.app_name_backend.helper_text') ),
                    ]),

                Section::make(__('acc::index.settings.form.section2.heading'))
                    ->description(__('acc::index.settings.form.section2.description'))
                    ->compact()
                    ->aside()
                    ->schema([
                        Toggle::make('registration_state')
                            ->label( __('acc::index.settings.form.section2.registration_state.title') )
                            ->hint( __('acc::index.settings.form.section2.registration_state.title') )
                            ->hintIcon('heroicon-o-question-mark-circle')
                            ->onColor('danger')
                            ->offColor('success'),
                    ]),




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([


                Tables\Columns\TextColumn::make('app_name')
                    ->label( __('acc::index.settings.table.app_name') ),

                Tables\Columns\TextColumn::make('app_name_backend')
                    ->label( __('acc::index.settings.table.app_name_backend') ),

                IconColumn::make('registration_state')
                    ->boolean()
                    ->falseIcon('heroicon-o-badge-check')
                    ->trueIcon('heroicon-o-x-circle')
                    ->falseColor('success')
                    ->trueColor('danger')


                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
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
            'index' => \Modules\Acc\Filament\Resources\SettingResource\Pages\ListSettings::route('/'),
            'create' => \Modules\Acc\Filament\Resources\SettingResource\Pages\CreateSetting::route('/create'),
            'edit' => \Modules\Acc\Filament\Resources\SettingResource\Pages\EditSetting::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationGroup(): string
    {
        return __('acc::index.filament.title.settings');
    }

    protected static function getNavigationLabel(): string
    {
        return __('acc::index.filament.label.website_settings');
    }

    public static function getBreadcrumb(): string
    {
        return __('acc::index.filament.label.website_settings');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

}
