<?php

namespace Modules\Address\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;

class AddressServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Address';

    protected string $nameLower = 'address';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(paths: module_path(name: $this->name, path: 'database/migrations'));
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path(path: 'lang/modules/'.$this->nameLower);

        if (is_dir(filename: $langPath)) {
            $this->loadTranslationsFrom(path: $langPath, namespace: $this->nameLower);
            $this->loadJsonTranslationsFrom(path: $langPath);
        } else {
            $this->loadTranslationsFrom(path: module_path(name: $this->name, path: 'lang'), namespace: $this->nameLower);
            $this->loadJsonTranslationsFrom(path: module_path(name: $this->name, path: 'lang'));
        }
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path(path: 'views/modules/'.$this->nameLower);
        $sourcePath = module_path(name: $this->name, path: 'resources/views');

        $this->publishes(paths: [$sourcePath => $viewPath], groups: ['views', $this->nameLower.'-module-views']);

        $this->loadViewsFrom(path: array_merge($this->getPublishableViewPaths(), [$sourcePath]), namespace: $this->nameLower);

        Blade::componentNamespace(config(key: 'modules.namespace').'\\'.$this->name.'\View\Components', $this->nameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    /**
     * Register commands in the format of Command::class.
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $configPath = module_path(name: $this->name, path: config(key: 'modules.paths.generator.config.path'));

        if (is_dir(filename: $configPath)) {
            $iterator = new \RecursiveIteratorIterator(iterator: new \RecursiveDirectoryIterator(directory: $configPath));

            foreach ($iterator as $file) {
                if ($file->isFile() && 'php' === $file->getExtension()) {
                    $config = str_replace(search: $configPath.DIRECTORY_SEPARATOR, replace: '', subject: $file->getPathname());
                    $config_key = str_replace(search: [DIRECTORY_SEPARATOR, '.php'], replace: ['.', ''], subject: $config);
                    $segments = explode(separator: '.', string: $this->nameLower.'.'.$config_key);

                    // Remove duplicated adjacent segments
                    $normalized = [];
                    foreach ($segments as $segment) {
                        if (end(array: $normalized) !== $segment) {
                            $normalized[] = $segment;
                        }
                    }

                    $key = ('config.php' === $config) ? $this->nameLower : implode(separator: '.', array: $normalized);

                    $this->publishes(paths: [$file->getPathname() => config_path(path: $config)], groups: 'config');
                    $this->merge_config_from(path: $file->getPathname(), key: $key);
                }
            }
        }
    }

    /**
     * Merge config from the given path recursively.
     */
    protected function merge_config_from(string $path, string $key): void
    {
        $existing = config(key: $key, default: []);
        $module_config = require $path;

        config(key: [$key => array_replace_recursive($existing, $module_config)]);
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config(key: 'view.paths') as $path) {
            if (is_dir(filename: $path.'/modules/'.$this->nameLower)) {
                $paths[] = $path.'/modules/'.$this->nameLower;
            }
        }

        return $paths;
    }
}
