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
        //
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
