<?php

namespace Thinktomorrow\Chief\Admin\Setup;

use Illuminate\Console\Command;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Managers\Exceptions\MissingResourceRegistration;
use Thinktomorrow\Chief\Managers\Register\Registry;

class CreateViewCommand extends Command
{
    protected $signature = 'chief:view {resourceKey : the managed modelkey of the model you want a view for}
                        {--force : overwrite existing class if it already exists}';

    protected $description = 'Create a chief admin view for a page or fragment';

    private FileManipulation $fileManipulation;
    private Registry $registry;

    public function __construct(Registry $registry, FileManipulation $fileManipulation)
    {
        parent::__construct();

        $this->registry = $registry;
        $this->fileManipulation = $fileManipulation;
    }

    public function handle()
    {
        $this->fileManipulation->setOutput($this->output);

        $resourceKey = strtolower($this->argument('resourceKey'));

        try {
            $resource = $this->registry->resource($resourceKey);
        } catch (MissingResourceRegistration $e) {
            $this->error('No model registrered via ' . $resourceKey);

            return;
        }

        $viewPath = $this->viewPath($resource::resourceKey(), $resource::modelClassName());

        $this->createView($resource::modelClassName(), $viewPath);
        $this->addMethod($resource::modelClassName(), $viewPath);
    }

    private function createView(string $modelClass, string $viewPath)
    {
        $stub = $this->implementsInterface($modelClass, Fragmentable::class)
            ? __DIR__.'/../../../resources/views/manager/windows/fragments/edit.blade.php'
            : __DIR__.'/../../../resources/views/manager/edit.blade.php';

        $fullViewPath = resource_path('views/' . $viewPath);

        $this->fileManipulation->writeFile($fullViewPath, file_get_contents($stub), $this->option('force'));
    }

    private function viewPath(string $viewKey, string $modelClass)
    {
        $path = $this->implementsInterface($modelClass, Fragmentable::class)
            ? 'fragments'
            : 'pages';

        return 'back/' . $path . '/' . $viewKey . '/edit.blade.php';
    }

    private function addMethod(string $modelClass, string $viewPath)
    {
        $path = str_replace('\\', '/', $modelClass) . '.php';

        $this->fileManipulation->addMethodToClass(base_path($path), $this->adminStub($modelClass, $viewPath));
    }

    private function adminStub(string $modelClass, string $viewPath): string
    {
        $path = str_replace('/', '.', $viewPath);
        $path = str_replace('.blade.php', '', $path);

        return "
    public function adminView(): \Illuminate\Contracts\View\View
    {
        return view('${path}');
    }
";
    }

    private function implementsInterface(string $modelClass, string $interfaceClass): bool
    {
        return (new \ReflectionClass($modelClass))->implementsInterface($interfaceClass);
    }
}
