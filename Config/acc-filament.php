<?php

use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Http\Middleware\MirrorConfigToSubpackages;
use Filament\Pages;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Fortify\Features;

/*
|--------------------------------------------------------------------------
| Admin Control Center for Filament
|--------------------------------------------------------------------------
|
| Here you can change the values for the Main Filament.
| Not the Module Filament. Keep it in mind
|
*/

config([
    'filament.layout.footer.should_show_logo' => false,
    'filament.layout.sidebar.is_collapsible_on_desktop' =>  true,
    'filament.dark_mode'    =>  true,
    'filament.layout.max_content_width' =>  'full',
    //'filament.brand'        =>  ,
//    'app.name'  =>  $settings->app_name,
]);


$settings = (\Illuminate\Support\Facades\Schema::hasTable('settings')) ? DB::select('select * from settings where id = 1') : '';

config([
    'app.name'          =>  $settings[0]->app_name         ?? 'Yezz.Design1',
    'filament.brand'    =>  $settings[0]->app_name_backend ?? 'Yezz.Backend1',
    'app.feature.registration'      =>  $settings[0]->registration_state ?? false,
    'media-library.path_generator'  =>  \Modules\Acc\Services\CustomPathGeneratorServices::class,
]);



if (config('app.feature.registration')){
    config(['fortify.features'  => [
            Features::registration(),
            Features::resetPasswords(),
            // Features::emailVerification(),
            Features::updateProfileInformation(),
            Features::updatePasswords(),
            Features::twoFactorAuthentication([
                'confirm' => true,
                'confirmPassword' => true,
                // 'window' => 0,
            ])]
    ]);
}

if (!config('app.feature.registration')){
    config(['fortify.features'  => [
        //Features::registration(),
        Features::resetPasswords(),
        // Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
            // 'window' => 0,
        ])]
    ]);
}







$moduleName = 'Acc';
$moduleCallName =   'AdminControlPanel';
$moduleNs = 'Modules\Acc';
$contextNs = 'Modules\\Acc\\Filament';
$contextPath = 'Filament';
return [

    /*
    |--------------------------------------------------------------------------
    | Filament Path
    |--------------------------------------------------------------------------
    |
    | The default is `admin` but you can change it to whatever works best and
    | doesn't conflict with the routing in your application.
    |
    */

    'path' => env('FILAMENT_PATH', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Filament Domain
    |--------------------------------------------------------------------------
    |
    | You may change the domain where Filament should be active. If the domain
    | is empty, all domains will be valid.
    |
    */

    'domain' => env('FILAMENT_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Pages
    |--------------------------------------------------------------------------
    |
    | This is the namespace and directory that Filament will automatically
    | register pages from. You may also register pages here.
    |
    */

    'pages' => [
        'namespace' => $contextNs.'\\Pages',
        //'path' => module_path($moduleName, "$contextPath/Pages"),
        'path' => base_path("Modules/$moduleName/$contextPath/Pages"),

        'register' => [
            Pages\Dashboard::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    |
    | This is the namespace and directory that Filament will automatically
    | register resources from. You may also register resources here.
    |
    */

    'resources' => [
        'namespace' => $contextNs.'\\Resources',
        //'path' => module_path($moduleName, "$contextPath/Resources"),
        'path' => base_path("Modules/$moduleName/$contextPath/Resources"),
        'register' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    |
    | This is the namespace and directory that Filament will automatically
    | register dashboard widgets from. You may also register widgets here.
    |
    */

    'widgets' => [
        'namespace' => $contextNs.'\\Widgets',
        //'path' => module_path($moduleName, "$contextPath/Widgets"),
        'path' => base_path("Modules/$moduleName/$contextPath/Widgets"),
        'register' => [
            Widgets\AccountWidget::class,
            Widgets\FilamentInfoWidget::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | This is the namespace and directory that Filament will automatically
    | register Livewire components inside.
    |
    */

    'livewire' => [
        'namespace' => $moduleNs.'\\Http\\Livewire',
        //'path' => module_path($moduleName, '/Http/Livewire'),
        'path' => base_path("Modules/$moduleName/Http/Livewire"),
    ],

    /*
    |--------------------------------------------------------------------------
    | Auth
    |--------------------------------------------------------------------------
    |
    | This is the configuration that Filament will use to handle authentication
    | into the admin panel.
    |
    */

    'auth' => [
        'guard' => env('FILAMENT_AUTH_GUARD', 'web'),
        'pages' => [
            'login' => \Modules\Acc\Livewire\Auth\FilamentLogin::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | You may customise the middleware stack that Filament uses to handle
    | requests.
    |
    */

    'middleware' => [
        'auth' => [
        //  Authenticate::class,
            \Modules\Acc\Middleware\FilamentMiddleware::class
        ],
        'base' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DispatchServingFilamentEvent::class,
            MirrorConfigToSubpackages::class,
        ],
    ],
];
