<?php

namespace KovaLiman\FrequentlyFiles;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class GenerateRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:routes {routeName} {index} {get} {post} {patch} {controllerNamespace} {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create rotes';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Class';

    public function handle()
    {
        $controllerNamespace = $this->argument('controllerNamespace');
        $routeName = $this->argument('routeName');
        $model = $this->argument('model');

        $commentName = ucfirst($routeName) .' routes';
        $comment = "/************************** $commentName *************************************/";
        
        $index = $this->argument('index');
        $indexRoute = "\nRoute::get('$routeName', [$controllerNamespace, 'index'])->name('$index')->middleware([]);";

        $get = $this->argument('get');
        $getRoute = "\nRoute::get('$routeName/{{$model}}', [$controllerNamespace, 'get'])->name('$get')->middleware([]);";

        $post = $this->argument('post');
        $postRoute = "\nRoute::post('$routeName', [$controllerNamespace, 'create'])->name('$post')->middleware([]);";

        $patch = $this->argument('patch');
        $patchRoute = "\nRoute::patch('$routeName/{{$model}}', [$controllerNamespace, 'update'])->name('$patch')->middleware([]);";

        $apiRouteFile = base_path('routes/api.php');

        file_put_contents($apiRouteFile, "\n", FILE_APPEND);
        file_put_contents($apiRouteFile, "\n", FILE_APPEND);
        file_put_contents($apiRouteFile, $comment, FILE_APPEND);
        file_put_contents($apiRouteFile, $indexRoute, FILE_APPEND);
        file_put_contents($apiRouteFile, $getRoute, FILE_APPEND);
        file_put_contents($apiRouteFile, $postRoute, FILE_APPEND);
        file_put_contents($apiRouteFile, $patchRoute, FILE_APPEND);

    }


}
