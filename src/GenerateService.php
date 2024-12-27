<?php

namespace KovaLiman\FrequentlyFiles;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Hash;
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

        $stub = str_replace('{{ namespace }}', $this->argument('namespace'), $stub);
        $stub = str_replace('{{ class }}', $this->argument('class'), $stub);
        $stub = str_replace('{{ interfaceNamespace }}', $this->argument('interfaceNamespace'), $stub);
        $stub = str_replace('{{ interfaceClassName }}', $this->argument('interfaceClassName'), $stub);
        $stub = str_replace('{{ model }}', $this->argument('model'), $stub);
        $stub = str_replace('{{ createDataNamespace }}', $this->argument('createDataNamespace'), $stub);
        $stub = str_replace('{{ createDataClass }}', $this->argument('createDataClass'), $stub);
        $stub = str_replace('{{ updateDataNamespace }}', $this->argument('updateDataNamespace'), $stub);
        $stub = str_replace('{{ updateDataClass }}', $this->argument('updateDataClass'), $stub);
        $stub = str_replace('{{ updateDataClass }}', $this->argument('updateDataClass'), $stub);
        $stub = str_replace('{{ interfaceVar }}', $this->argument('interfaceVar'), $stub);
        $stub = str_replace('{{ interfaceImport }}', $this->argument('interfaceImport'), $stub);
        $stub = str_replace('{{ directoryName }}', $this->argument('directoryName'), $stub);
        $stub = str_replace('{{ createDTOImport }}', $this->argument('createDTOImport'), $stub);
        $stub = str_replace('{{ updateDTOImport }}', $this->argument('updateDTOImport'), $stub);
        $stub = str_replace('{{ modelVar }}', $this->argument('modelVar'), $stub);

        $stub = str_replace('{{ creteDataArray }}', $this->createData(), $stub);
        $stub = str_replace('{{ updateDataArray }}', $this->updateData(), $stub);

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

        return str_replace('DummyService', $this->argument('name'), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/Stubs/DummyService.stub';
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
            ['interfaceNamespace', InputArgument::REQUIRED],
            ['interfaceClassName', InputArgument::REQUIRED],
            ['model', InputArgument::REQUIRED],
            ['createDataNamespace', InputArgument::REQUIRED],
            ['createDataClass', InputArgument::REQUIRED],
            ['updateDataNamespace', InputArgument::REQUIRED],
            ['updateDataClass', InputArgument::REQUIRED],
            ['interfaceVar', InputArgument::REQUIRED],
            ['interfaceImport', InputArgument::REQUIRED],
            ['directoryName', InputArgument::REQUIRED],
            ['createDTOImport', InputArgument::REQUIRED],
            ['updateDTOImport', InputArgument::REQUIRED],
            ['modelVar', InputArgument::REQUIRED],
            ['properties', InputArgument::REQUIRED]
        ];
    }

    public function createData()
    {
        $properties = $this->argument('properties');
        $total = count($properties)-1;
        if (!count($properties)){
            return '//todo';
        }
        $data = '';
        for ($i = 0; $i<=$total-1; $i++){
            $name = $properties[$i]->name;
            $data .= "'$name' => $" . "createData->".$name.",";
            if ($i<$total){
                $data .= "\n\t\t\t";
            }
        }
        return $data;
    }

    public function updateData()
    {
        $properties = $this->argument('properties');
        $total = count($properties)-1;
        if (!count($properties)){
            return '//todo';
        }
        $data = '';
        for ($i = 0; $i<=$total-1; $i++){
            $name = $properties[$i]->name;
            $data .= "'$name' => $" . "updateData->".$name." ?? $"."updateData->".$this->argument('modelVar')."->".$name.",";
            if ($i<$total){
                $data .= "\n\t\t\t";
            }
        }
        return $data;
    }
}
