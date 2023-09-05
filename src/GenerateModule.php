<?php

namespace KovaLiman\FrequentlyFiles;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class GenerateModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call('make:interface', [
            'name' => "App\\".env('APP_NAME')."\\".$this->argument('name')."\\Contracts\\".Str::singular($this->argument('name'))."Repository"
        ]);

        Artisan::call('make:concrete', [
            'name' => "App\\".env('APP_NAME')."\\".$this->argument('name')."\\Repositories\\EloquentRepository"
        ]);

        Artisan::call('make:service', [
            'name' => "App\\".env('APP_NAME')."\\".$this->argument('name')."\\Services\\".Str::singular($this->argument('name'))."Service"
        ]);

        Artisan::call('make:trait', [
            'name' => "App\\".env('APP_NAME')."\\".$this->argument('name')."\\Traits\ModelTrait"
        ]);
    }
}
