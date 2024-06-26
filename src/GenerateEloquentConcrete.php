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

        $stub = str_replace('{{ interfaceClassName }}', $this->argument('interfaceClassName'), $stub);
        $stub = str_replace('{{ namespace }}', $this->argument('namespace'), $stub);
        $stub = str_replace('{{ model }}', $this->argument('model'), $stub);
        $stub = str_replace('{{ interfaceNamespace }}', $this->argument('interfaceNamespace'), $stub);
        $stub = str_replace('{{ interfaceImport }}', $this->argument('interfaceImport'), $stub);
        $stub = str_replace('{{ directoryName }}', $this->argument('directoryName'), $stub);

        return $this->replaceClass($stub, $name);
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

        return str_replace('DummyEloquentConcrete', $this->argument('namespace'), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/Stubs/DummyEloquentConcrete.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->argument('namespace');
    }

    protected function getNameInput()
    {
        return parent::getNameInput(); // TODO: Change the autogenerated stub
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
            ['namespace', InputArgument::REQUIRED],
            ['interfaceClassName', InputArgument::REQUIRED],
            ['interfaceNamespace', InputArgument::REQUIRED],
            ['model', InputArgument::REQUIRED],
            ['interfaceImport', InputArgument::REQUIRED],
            ['directoryName', InputArgument::REQUIRED],
        ];
    }
}
