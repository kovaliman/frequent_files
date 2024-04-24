<?php

namespace KovaLiman\FrequentlyFiles;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class GenerateController extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:ff-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a ff controller';

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
        $stub = str_replace('{{ class }}', $this->argument('class'), $stub);
        $stub = str_replace('{{ createDTOImport }}', $this->argument('createDTOImport'), $stub);
        $stub = str_replace('{{ updateDTOImport }}', $this->argument('updateDTOImport'), $stub);
        $stub = str_replace('{{ modelVar }}', $this->argument('modelVar'), $stub);
        $stub = str_replace('{{ serviceName }}', $this->argument('serviceName'), $stub);
        $stub = str_replace('{{ serviceVar }}', $this->argument('serviceVar'), $stub);
        $stub = str_replace('{{ createRequestImport }}', $this->argument('createRequestImport'), $stub);
        $stub = str_replace('{{ updateRequestImport }}', $this->argument('updateRequestImport'), $stub);
        $stub = str_replace('{{ serviceImport }}', $this->argument('serviceImport'), $stub);

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

        return str_replace('DummyController', $this->argument('name'), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/Stubs/DummyController.stub';
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
            ['class', InputArgument::REQUIRED],
            ['createDTOImport', InputArgument::REQUIRED],
            ['updateDTOImport', InputArgument::REQUIRED],
            ['modelVar', InputArgument::REQUIRED],
            ['serviceName', InputArgument::REQUIRED],
            ['serviceVar', InputArgument::REQUIRED],
            ['createRequestImport', InputArgument::REQUIRED],
            ['updateRequestImport', InputArgument::REQUIRED],
            ['serviceImport', InputArgument::REQUIRED],
        ];
    }
}
