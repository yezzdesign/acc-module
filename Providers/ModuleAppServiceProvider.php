<?php
/**
 * ModuleFilamentServiceProvider.php
 *
 * Plugin to load filament resources, pages and widgets for
 * nwidart/laravel-modules
 *
 * @author      jgmuchiri
 * @license     MIT
 * @copyright   Copyright (c) 2023, John Muchiri
 * @version     1.1
 */

namespace Modules\Acc\Providers;


use Filament\PluginServiceProvider;
use Illuminate\Support\Facades\DB;
use Modules\Acc\Services\CustomPathGeneratorServices;
use Spatie\LaravelPackageTools\Package;

class ModuleAppServiceProvider extends PluginServiceProvider
{
    public static string $name = 'files';

    private $modules = [];

    function __construct($app)
    {
        parent::__construct($app);

        $this->loadModules();

        $this->registerFilament('pages');
        $this->registerFilament('resources');
        $this->registerFilament('widgets');

        $this->app->booting(function () {
            $this->registerConfigs();
        });
    }

    public function configurePackage(Package $package): void
    {
        parent::configurePackage($package);
    }

    function registerFilament($type){
        foreach($this->modules as $module){

            $path = 'Modules\\'.$module.'\Filament\\'.ucfirst($type).'\\';

            $dir = base_path('Modules/'.$module.'/Filament/'.ucfirst($type));

            if(is_dir($dir)){

                $files = scandir($dir);

                foreach($files as $file)
                {
                    if(pathinfo($file, PATHINFO_EXTENSION) !=='php') continue;

                    $class = $path.basename($file,'.php');
                    config()->push('filament.'.strtolower($type).'.register',$class);
                }
            }
        }
    }

    function loadModules(){

        $module_statuses = json_decode(file_get_contents(base_path().'/modules_statuses.json'));
        foreach ($module_statuses as $name => $active) {
            if (!$active) continue;
            $this->modules[] = $name;
        }
        //$this->modules[]    =   'Library';
        //$this->modules[]    =   'Acc';

    }

    private function registerConfigs()
    {
        foreach ($this->modules as $module)
        {
            $path   =   base_path().'/Modules/'.$module.'/Config/filament.php';
            if (file_exists($path)){
                $this->mergeConfigFrom($path, strtolower($module));
            }
        }

    }
}
