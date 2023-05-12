<?php

namespace Modules\Acc\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        foreach (array_diff(scandir(base_path('Modules')), array('..', '.')) as $path)
        {
            $file   =   'Modules/'.$path.'/Providers/FilamentServiceProvider.php';
            if(file_exists(base_path($file)))
            {
                $this->app->register('Modules\\'.$path.'\Providers\FilamentServiceProvider' );
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
