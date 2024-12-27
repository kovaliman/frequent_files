<?php

namespace KovaLiman\FrequentlyFiles;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class GenerateUpdateTest extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:update-test';

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
    protected $type = 'Test';

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $stub = str_replace('{{ namespace }}', $this->argument('namespace'), $stub);
        $stub = str_replace('{{ model }}', $this->argument('model'), $stub);
        $stub = str_replace('{{ modelVar }}', $this->argument('modelVar'), $stub);
        $stub = str_replace('{{ route }}', $this->argument('route'), $stub);
        $stub = str_replace('{{ modelNamespace }}', $this->argument('modelNamespace'), $stub);
        $stub = str_replace('{{ updateData }}', $this->makeUpdateData(), $stub);
        $stub = str_replace('{{ assertation }}', $this->makeAssertation(), $stub);
        
        return $this->replaceClass($stub, $name);
    }
    

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/Stubs/tests/DummyUpdateTest.stub';
    }

    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace('DummyUpdateTest', $this->argument('name'), $stub);
    }
    

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the test.'],
            ['namespace', InputArgument::REQUIRED],
            ['model', InputArgument::REQUIRED],
            ['modelVar', InputArgument::REQUIRED],
            ['route', InputArgument::REQUIRED],
            ['modelNamespace', InputArgument::REQUIRED],
            ['properties', InputArgument::REQUIRED],
        ];
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return base_path('tests').str_replace('\\', '/', $name).'.php';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Feature';
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return 'Tests';
    }

    public function makeUpdateData()
    {
        $properties = $this->argument('properties');
        $total = count($properties);
        if (!$total){
            return '//todo';
        }

        $data = '';
        for ($i = 0; $i<=$total-1; $i++){
            $name = $properties[$i]->name;
            $data .= "'$name' => ".$this->fakeData($properties[$i]->type).",";
            if ($i< $total - 1){
                $data .= "\n\t\t\t";
            }
        }
        return $data;
    }

    public function fakeData($type)
    {
        if ($type === 'boolean'){
            return true;
        }

        if ($type === 'integer'){
            return rand(1, 20);
        }

        if ($type === 'string'){
            return "'".fake()->text(20)."'";
        }

        if ($type === 'text'){
            return "'".fake()->text(50)."'";
        }

        return '';
    }

    public function makeAssertation()
    {
        $properties = $this->argument('properties');
        $total = count($properties);
        if (!$total){
            return '//todo';
        }

        $data = '';
        for ($i = 0; $i<=$total-1; $i++){
            $name = $properties[$i]->name;
            $data .= '$this->assertTrue($response->original->'.$name .' === $record->'.$name.');';
            if ($i< $total - 1){
                $data .= "\n\t\t";
            }
        }
        return $data;
    }
}
