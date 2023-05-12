<?php

namespace Modules\Acc\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\UserMenuItem;
use Savannabits\FilamentModules\ContextServiceProvider;

class FilamentServiceProvider extends ContextServiceProvider
{
    public static string $name = 'acc-filament';
    public static string $module = 'Acc';

    public function packageRegistered(): void
    {
        $this->app->booting(function () {
            $this->registerConfigs();
        });
        parent::packageRegistered();
    }

    public function registerConfigs() {
        $this->mergeConfigFrom(
            app('modules')->findOrFail(static::$module)->getExtraPath( 'Config/'.static::$name.'.php'),
            static::$name
        );
    }

    public function boot()
    {
        parent::boot();

        Filament::serving(function () {
            Filament::forContext('filament', function () {

                Filament::registerNavigationGroups([
                    NavigationGroup::make()
                        ->label('Settings')
                        ->icon('heroicon-s-cog')
                        ->collapsed(),
                    NavigationGroup::make()
                        ->label('Modules')
                        ->icon('heroicon-s-shopping-cart'),

                ]);
            });
        });

        Filament::serving(function () {
            Filament::forContext('filament', function (){
                Filament::registerNavigationItems([

                    NavigationItem::make(static::$module)
                        ->label(__('acc::index.filament.title.website_settings'))
                        ->url(route(static::$name.'.resources.website-settings.index'))
                        ->icon('heroicon-o-adjustments')
                        ->group('Settings'),

                    NavigationItem::make(static::$module)
                        ->label(config('acc.linkName'))
                        ->url(route(static::$name.'.pages.dashboard'))
                        ->icon('heroicon-o-adjustments')
                        ->group('Modules'),

                ]);
            });
        });

        Filament::serving(function () {
            Filament::registerUserMenuItems([
                'account' => UserMenuItem::make()->url(route('profile.show')),
                // ...
            ]);
        });

    }
}
