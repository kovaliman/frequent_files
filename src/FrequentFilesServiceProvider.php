<?php

namespace KovaLiman\FrequentlyFiles;

use Illuminate\Support\ServiceProvider;

class FrequentFilesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__.'/config/frequent-files.php' => $this->app->configPath('frequent-files.php'),
        ], 'frequent-files');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            GenerateModule::class,
            GenerateService::class,
            GenerateInterface::class,
            GenerateEloquentConcrete::class,
            GenerateCreateDTO::class,
            GenerateUpdateDTO::class,
            GenerateController::class,
            GenerateRoutes::class,
            GenerateIndexTest::class,
            GenerateGetTest::class,
            GenerateCreateTest::class,
            GenerateUpdateTest::class,
            GenerateCreateValidationTest::class,
            GenerateUpdateValidationTest::class,
        ]);
    }
}
