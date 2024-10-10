<?php

namespace Dotswan\ModulesAutoDiscover;

use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Laravel\Module;

class ModulesAutoDiscoverServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $modules = app()->make('modules')->allEnabled();
        $this->autoDiscovery($modules);
    }

    protected function autoDiscovery(array $modules): void
    {
        foreach ($modules as $module) {

            if ($module->get('auto-discovery') === false) {
                continue;
            }

            $this->registerConfigs($module);
            $this->registerTranslations($module);
        }

    }

    protected function registerConfigs(Module $module): void
    {
        if (file_exists(base_path('bootstrap/cache/config.php'))) {
            return;
        }

        $configPath = "{$module->getPath()}/".config('modules.paths.generator.config.path', 'Config');

        if (! is_dir($configPath)) {
            return;
        }

        foreach (glob($configPath.'/*.php') as $configFile) {
            $filename = pathinfo($configFile, PATHINFO_FILENAME);
            config()->set($filename, array_merge(config()->get($filename, []), require $configFile));
        }
    }

    public function registerTranslations(Module $module): void
    {
        $translationPath = "{$module->getPath()}/".config('modules.paths.generator.lang.path', 'Lang');

        if (! is_dir($translationPath)) {
            return;
        }

        $this->loadJsonTranslationsFrom($translationPath);
        $this->loadTranslationsFrom($translationPath, $module->getLowerName());

    }
}
