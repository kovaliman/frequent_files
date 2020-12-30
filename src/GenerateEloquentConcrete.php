<?php

namespace KovaLiman\FrequentlyFiles;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class GenerateEloquentConcrete extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:concrete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new concrete eloquent class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Class';

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceImplement($stub, $name)->addImport($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * @param $stub
     * @param $name
     * @return $this
     */
    protected function addImport(&$stub, $name)
    {
        $import = implode("\\", explode("\\", $name, '-2'))."\\Contracts\\";

        $stub = str_replace('{{ importConcrete }}', $import, $stub);

        return $this;
    }

    /**
     * @param $stub
     * @param $name
     * @return $this
     */
    protected function replaceImplement(&$stub, $name)
    {
        $concrete = Str::singular(array_slice(explode("\\", $name), '-3', '1')[0]);

        $stub = str_replace('{{ concrete }}', $concrete, $stub);

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);


        return str_replace('DummyEloquentConcrete', $this->argument('name'), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/Stubs/DummyEloquentConcrete.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class.'],
        ];
    }
}
