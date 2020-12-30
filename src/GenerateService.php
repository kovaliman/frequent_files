<?php

namespace KovaLiman\FrequentlyFiles;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class GenerateService extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a basic model service';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Class';

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
            ->replaceRepository($stub, $name)
            ->replaceClass($stub, $name);
    }

    protected function replaceRepository(&$stub, $name)
    {
        $verb = array_slice(explode("\\", $name), '-3', '1')[0];

        $singular = Str::singular($verb);

        $repo_var = Str::lower($singular)."Repository";

        $repo = $singular."Repository";

        $repo_path = "App\\".env('APP_NAME')."\\".$verb."\\Contracts\\".$repo;

        $stub = str_replace('{{ repo_var }}', $repo_var, $stub);

        $stub = str_replace('{{ repository }}', $repo, $stub);

        $stub = str_replace('{{ repository_path }}', $repo_path, $stub);


        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace('DummyService', $this->argument('name'), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  __DIR__.'/Stubs/DummyService.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the contract.'],
        ];
    }
}
