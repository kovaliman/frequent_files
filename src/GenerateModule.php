<?php

namespace KovaLiman\FrequentlyFiles;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use KovaLiman\FrequentlyFiles\DTO\PropertyDTO;
use Symfony\Component\Console\Input\InputArgument;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

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
            $directoryName = config('frequent-files.directory');
            $controllerDirectory = config('frequent-files.controller_directory');

            $moduleName = text('Module name');

            $properties = $this->getModelProperties();

            $moduleVar = lcfirst($moduleName);

            $controllerName = $moduleName . 'Controller';
            $additionalDirectory = $controllerDirectory ? '\\' . $controllerDirectory : '';
            $controllerNamespace = 'App\\Http\\Controllers' . $additionalDirectory;
            $createRequestImport = 'App\\Http\\Requests\\' . $moduleName . '\\CreateRequest';
            $updateRequestImport = 'App\\Http\\Requests\\' . $moduleName . '\\UpdateRequest';


            $interfaceNamespace = "App\\" . $directoryName . "\\" . $moduleName . "\\Contracts";
            $interfaceClassName = $moduleName . "Repository";
            $interfaceVar = lcfirst($moduleName) . "Repository";
            $interaceImport = $interfaceNamespace . "\\" . $interfaceClassName;

            $repositoryNamespace = "App\\" . $directoryName . "\\" . $moduleName . "\\Repositories";
            $repositoryClassName = "EloquentRepository";

            $serviceNamespace = "App\\" . $directoryName . "\\" . $moduleName . "\\Services";
            $serviceClassName = $moduleName . "Service";
            $serviceImport = $serviceNamespace . '\\' . $serviceClassName;
            $serviceVar = lcfirst($serviceClassName);

            $createDTONamespace = "App\\" . $directoryName . "\\" . $moduleName . "\\DTO";
            $createDTOClassName = "CreateData";
            $createDTOImport = $createDTONamespace . '\\' . $createDTOClassName;

            $updateDTONamespace = "App\\" . $directoryName . "\\" . $moduleName . "\\DTO";
            $updateDTOClassName = "UpdateData";
            $updateDTOImport = $updateDTONamespace . '\\' . $updateDTOClassName;

            Artisan::call('make:model ' . $moduleName . ' -s -f -m');

            Artisan::call('make:create-request', [
                'name' => 'CreateRequest',
                'namespace' =>  "App\\Http\\Requests\\" . $moduleName,
                'properties' => $properties
            ]);

            Artisan::call('make:update-request', [
                'name' => 'UpdateRequest',
                'namespace' =>  "App\\Http\\Requests\\" . $moduleName,
                'properties' => $properties
            ]);

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
                'model' => $moduleName,
            ]);

            Artisan::call('make:interface', [
                'name' => $interfaceClassName,
                'namespace' => $interfaceNamespace,
                'className' => $interfaceClassName,
                'directoryName' => $directoryName,
            ]);

            Artisan::call('make:concrete', [
                'name' => $repositoryClassName,
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
                'properties' => $properties,
            ]);

            Artisan::call('make:create-dto', [
                'name' => $createDTOClassName,
                'namespace' => $createDTONamespace,
                'directoryName' => $directoryName,
                'properties' => $properties,
            ]);

            Artisan::call('make:update-dto', [
                'name' => $updateDTOClassName,
                'namespace' => $updateDTONamespace,
                'directoryName' => $directoryName,
                'model' => $moduleName,
                'modelVar' => $moduleVar,
                'properties' => $properties,
            ]);

            $routeName = Str::kebab($moduleName);

            $indexRoute = 'api.' . $routeName . '.index';
            $getRoute = 'api.' . $routeName . '.get';
            $postRoute = 'api.' . $routeName . '.create';
            $patchRoute = 'api.' . $routeName . '.update';

            Artisan::call('make:routes', [
                'routeName' => $routeName,
                'index' => $indexRoute,
                'get' => $getRoute,
                'post' => $postRoute,
                'patch' => $patchRoute,
                'controllerNamespace' => '\\' . $controllerNamespace . '\\' . $controllerName . '::class',
                'model' => lcfirst($moduleName),
            ]);

            Artisan::call('make:index-test', [
                'name' => "Tests\Feature\\" . $moduleName . "\GetIndexTest",
                'namespace' => "Tests\Feature\\" . $moduleName,
                'model' => $moduleName,
                'modelVar' => $moduleVar,
                'route' => $indexRoute,
                'modelNamespace' => 'App\\Models\\' . $moduleName
            ]);

            Artisan::call('make:get-test', [
                'name' => "Tests\Feature\\" . $moduleName . "\GetByIdTest",
                'namespace' => "Tests\Feature\\" . $moduleName,
                'model' => $moduleName,
                'modelVar' => $moduleVar,
                'route' => $getRoute,
                'modelNamespace' => 'App\\Models\\' . $moduleName
            ]);

            Artisan::call('make:create-test', [
                'name' => "Tests\Feature\\" . $moduleName . "\CreateTest",
                'namespace' => "Tests\Feature\\" . $moduleName,
                'model' => $moduleName,
                'modelVar' => $moduleVar,
                'route' => $postRoute,
                'modelNamespace' => 'App\\Models\\' . $moduleName,
                'properties' => $properties,
            ]);

            Artisan::call('make:update-test', [
                'name' => "Tests\Feature\\" . $moduleName . "\UpdateTest",
                'namespace' => "Tests\Feature\\" . $moduleName,
                'model' => $moduleName,
                'modelVar' => $moduleVar,
                'route' => $patchRoute,
                'modelNamespace' => 'App\\Models\\' . $moduleName,
                'properties' => $properties,
            ]);

            Artisan::call('make:create-validation-test', [
                'name' => "Tests\Feature\\" . $moduleName . "\CreateValidationTest",
                'namespace' => "Tests\Feature\\" . $moduleName,
                'model' => $moduleName,
                'modelVar' => $moduleVar,
                'route' => $postRoute,
                'modelNamespace' => 'App\\Models\\' . $moduleName,
                'properties' => $properties,
            ]);

            Artisan::call('make:update-validation-test', [
                'name' => "Tests\Feature\\" . $moduleName . "\UpdateValidationTest",
                'namespace' => "Tests\Feature\\" . $moduleName,
                'model' => $moduleName,
                'modelVar' => $moduleVar,
                'route' => $patchRoute,
                'modelNamespace' => 'App\\Models\\' . $moduleName,
                'properties' => $properties,
            ]);

            $this->info($moduleName . ' module created');
        } catch (\Exception $exception) {
            dd($exception);
        }

    }

    public function getModelProperties()
    {
        $properties = [];

        $wantProps = select(
            label: 'Do you want to add properties?',
            options: ['Yes', 'No']
        );

        while ($wantProps === 'Yes') {
            $name = text('Property name');
            $type = select(
                label: 'Property type',
                options: ['string', 'integer', 'boolean', 'text']
            );
            $required = select(
                label: 'Is required?',
                options: ['Yes', 'No'],
                default: 'Yes'
            );
            $prop = new PropertyDTO($name, $type, $required === 'Yes');
            $properties[] = $prop;
            foreach ($properties as $property){
                $this->info($property->name);
            }
            $wantProps = select(
                label: 'Add another properties?',
                options: ['Yes', 'No']
            );
        }

        return $properties;
    }
}
