<?php

namespace Thinktomorrow\Chief\Admin\Setup;

use Illuminate\Console\Command;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Managers\Exceptions\MissingModelRegistration;
use Thinktomorrow\Chief\Managers\Register\Registry;

class CreateViewCommand extends Command
{
    protected $signature = 'chief:view {managedModelKey : the managed modelkey of the model you want a view for}
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

        $managedModelKey = strtolower($this->argument('managedModelKey'));

        try {
            $modelClass = $this->registry->modelClass($managedModelKey);
        } catch (MissingModelRegistration $e) {
            $this->error('No model registrered via ' . $managedModelKey);

            return;
        }

        $this->createView($modelClass);
        $this->addMethod($modelClass);
    }

    private function createView(string $modelClass)
    {
        $stub = $this->implementsInterface($modelClass, Fragmentable::class)
            ? __DIR__.'/../../../resources/views/manager/windows/fragments/edit.blade.php'
            : __DIR__.'/../../../resources/views/manager/edit.blade.php';

        $fullViewPath = resource_path('views/' . $this->viewPath($modelClass));
        $this->fileManipulation->writeFile($fullViewPath, file_get_contents($stub), $this->option('force'));
    }

    private function viewPath(string $modelClass)
    {
        $path = $this->implementsInterface($modelClass, Fragmentable::class)
            ? 'fragments'
            : 'pages';

        return 'back/' . $path . '/' . $modelClass::managedModelKey() . '/edit.blade.php';
    }

    private function addMethod(string $modelClass)
    {
        $path = str_replace('\\', '/', $modelClass) . '.php';

        $this->fileManipulation->addMethodToClass(base_path($path), $this->adminStub($modelClass));
    }

    private function adminStub(string $modelClass): string
    {
        $path = $this->viewPath($modelClass);
        $path = str_replace('/', '.', $path);
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
