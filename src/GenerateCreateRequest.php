<?php

namespace KovaLiman\FrequentlyFiles;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use KovaLiman\FrequentlyFiles\DTO\DTOPropertyParser;
use Symfony\Component\Console\Input\InputArgument;

class GenerateCreateRequest extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:create-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create request';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Class';

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $stub = str_replace('{{ class }}', 'CreateRequest', $stub);
        $stub = str_replace('{{ namespace }}', $this->argument('namespace'), $stub);
        $stub = str_replace('{{ rules }}', $this->parseRules(), $stub);

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

        return str_replace('DummyRequest', $this->argument('name'), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/Stubs/DummyRequest.stub';
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
            ['properties', InputArgument::REQUIRED],
        ];
    }


    public function parseRules()
    {
        $properties = $this->argument('properties');
        $total = count($properties);
        if (!count($properties)) {
            return '//todo';
        }
        $data = '';
        for ($i = 0; $i <= $total - 1; $i++) {
            $name = $properties[$i]->name;
            $requirable = $properties[$i]->required ? 'required' : 'nullable';
            $type = $this->type($properties[$i]->type);
            $data .= "'$name' => ['bail', '$requirable', '$type'";
            if (in_array($type, ['string', 'text', 'integer'])) {
                $data .= ", 'min:1'";
            }
            if (in_array($properties[$i]->type, ['string', 'text'])) {
                if ($properties[$i]->type === 'string') {
                    $data .= ", 'max:255'";
                }
                if ($properties[$i]->type === 'text') {
                    $data .= ", 'max:10000'";
                }
            }
            if ($properties[$i]->type === 'integer') {
                $data .= ", 'max:10000000'";
            }
            $data .= '],';
            if ($i < $total - 1) {
                $data .= "\n\t\t\t";
            }
        }

        return $data;
    }

    public function type(string $type)
    {
        if (in_array($type, ['string', 'text'])) {
            return 'string';
        }

        return $type;
    }
}