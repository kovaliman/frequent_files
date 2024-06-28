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
    protected $signature = 'generate:module';

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
        try {
            $directoryName = ucfirst(strtolower($this->ask('Direcory name?')));

            $moduleName = ucfirst(Str::camel(Str::singular(strtolower($this->ask('Module name?')))));

            $moduleVar = Str::camel($moduleName);

            $wantController = $this->confirm('Do you want controller');

            $wantModel = $this->confirm('Do you want model');
            if ($wantController) {
                $controllerDirectory = $this->ask('In which directory should set controller');
//                $wantRoutes = $this->confirm('Do you want GET, POST and PATCH routes');
                $controllerName = $moduleName.'Controller';
                $additionalDirectory = $controllerDirectory ? '\\'.$controllerDirectory : '';
                $controllerNamespace = 'App\\Http\\Controllers' . $additionalDirectory;
                $createRequestImport = 'App\\Http\\Requests\\'.$moduleName.'\\CreateRequest';
                $updateRequestImport = 'App\\Http\\Requests\\'.$moduleName.'\\UpdateRequest';
            }
            
            if ($wantModel) {
                Artisan::call('make:model ' . $moduleName . ' -s -f -m');
            }
            
            $interfaceNamespace = "App\\" . $directoryName . "\\" . $moduleName . "\\Contracts";
            $interfaceClassName = $moduleName . "Repository";
            $interfaceVar = lcfirst($moduleName);
            $interaceImport = $interfaceNamespace . "\\" . $interfaceClassName;

            $repositoryNamespace = "App\\" . $directoryName . "\\" . $moduleName . "\\Repositories";
            $repositoryClassName = "EloquentRepository";

            $serviceNamespace = "App\\" . $directoryName . "\\" . $moduleName . "\\Services";
            $serviceClassName = $moduleName . "Service";
            $serviceImport = $serviceNamespace .'\\'.$serviceClassName;
            $serviceVar = lcfirst($serviceClassName);
            
            $createDTONamespace = "App\\" . $directoryName . "\\" . $moduleName . "\\DTO";
            $createDTOClassName = "CreateData";
            $createDTOImport = $createDTONamespace.'\\'.$createDTOClassName;

            $updateDTONamespace = "App\\" . $directoryName . "\\" . $moduleName . "\\DTO";
            $updateDTOClassName = "UpdateData";
            $updateDTOImport = $updateDTONamespace.'\\'.$updateDTOClassName;
            
            if ($wantController) {
                Artisan::call('make:request ' . $moduleName . '/CreateRequest');
                Artisan::call('make:request ' . $moduleName . '/UpdateRequest');
                
                Artisan::call('make:ff-controller', [
                    'name' => $controllerName,
                    'class' => $controllerName,
                    'namespace' => $controllerNamespace,
                    'createDTOImport' => $createDTOImport,
                    'updateDTOImport' => $updateDTOImport,
                    'modelVar' => $moduleVar,
                    'serviceName' => $serviceClassName,
                    'serviceVar' => $serviceVar,
                    'createRequestImport' => $createRequestImport,
                    'updateRequestImport' => $updateRequestImport,
                    'serviceImport' => $serviceImport,
                ]);
            }
            
            Artisan::call('make:interface', [
                'name' => $interfaceClassName,
                'namespace' => $interfaceNamespace,
                'className' => $interfaceClassName,
                'directoryName' => $directoryName,
            ]);

            Artisan::call('make:concrete', [
                'name' => $repositoryNamespace,
                'namespace' => $repositoryNamespace,
                'interfaceClassName' => $interfaceClassName,
                'interfaceNamespace' => $interfaceNamespace,
                'model' => $moduleName,
                'interfaceImport' => $interaceImport,
                'directoryName' => $directoryName,
            ]);

            Artisan::call('make:service', [
                'name' => $serviceClassName,
                'namespace' => $serviceNamespace,
                'class' => $serviceClassName,
                'interfaceNamespace' => $interfaceNamespace,
                'interfaceClassName' => $interfaceClassName,
                'model' => $moduleName,
                'createDataNamespace' => $createDTONamespace,
                'createDataClass' => $createDTOClassName,
                'updateDataNamespace' => $updateDTONamespace,
                'updateDataClass' => $updateDTOClassName,
                'interfaceVar' => $interfaceVar,
                'interfaceImport' => $interaceImport,
                'directoryName' => $directoryName,
                'createDTOImport' => $createDTOImport,
                'updateDTOImport' => $updateDTOImport,
                'modelVar' => $moduleVar,
            ]);

            Artisan::call('make:create-dto', [
                'name' => $createDTOClassName,
                'namespace' => $createDTONamespace,
                'directoryName' => $directoryName,
            ]);

            Artisan::call('make:update-dto', [
                'name' => $updateDTOClassName,
                'namespace' => $updateDTONamespace,
                'directoryName' => $directoryName,
                'model' => $moduleName,
                'modelVar' => $moduleVar,
            ]);

           

        } catch (\Exception $exception) {
            dd($exception);
        }

    }
}
