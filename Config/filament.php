<?php

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Features;

/*
|--------------------------------------------------------------------------
| Admin Control Center for Filament
|--------------------------------------------------------------------------
|
| Here you can change the values for the Main Config Files. This includes
| the Main Filament config.
| Not the Module Filament or the Module himself. Keep it in mind
|
*/
$settings = (\Illuminate\Support\Facades\Schema::hasTable('settings')) ? DB::select('select * from settings where id = 1') : '';

config([
    'app.name'                      =>  $settings[0]->app_name           ?? 'Yezz.Default',
    'filament.brand'                =>  $settings[0]->app_name_backend   ?? 'Yezz.Back.Default',
    'app.feature.registration'      =>  $settings[0]->registration_state ?? false,
    'media-library.path_generator'  =>  \Modules\Acc\Services\CustomPathGeneratorServices::class,
    'app.locale'    =>  'de',
    'app.faker_locale'  =>  'de_DE',
    'filament.layout.footer.should_show_logo' => false,
    'filament.layout.sidebar.is_collapsible_on_desktop' =>  true,
    'filament.dark_mode'    =>  true,
    'filament.layout.max_content_width' =>  'full',
]);


Filament::serving(function () {
    Filament::registerUserMenuItems([
        'account' => UserMenuItem::make()->url(route('profile.show')),
        // ...
    ]);
});


/*
|--------------------------------------------------------------------------
| Main Features for JetStream
|--------------------------------------------------------------------------
|
| Here you can change the Features for the Main Jetstream.
| Not the Module himself. Keep it in mind
|
*/
if (!config('app.feature.registration')) {config(['fortify.features'  => [
        Features::resetPasswords(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true, ])]]);}

return [
    'app_name'  =>  'Test',
];
