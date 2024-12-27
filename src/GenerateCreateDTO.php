<?php

namespace KovaLiman\FrequentlyFiles;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use KovaLiman\FrequentlyFiles\DTO\DTOPropertyParser;
use Symfony\Component\Console\Input\InputArgument;

class GenerateCreateDTO extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:create-dto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create create dto';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Class';

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $stub = str_replace('{{ namespace }}', $this->argument('namespace'), $stub);
        $stub = str_replace('{{ directoryName }}', $this->argument('directoryName'), $stub);
        $stub = str_replace('{{ properties }}', DTOPropertyParser::parseProperties($this->argument('properties')), $stub);
        
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

        return str_replace('DummyCreateDTO', $this->argument('name'), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/Stubs/DummyCreateDTO.stub';
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

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the contract.'],
            ['namespace', InputArgument::REQUIRED],
            ['directoryName', InputArgument::REQUIRED],
            ['properties', InputArgument::REQUIRED]
        ];
    }
}
