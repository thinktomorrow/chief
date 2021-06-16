<?php

namespace Thinktomorrow\Chief\Admin\Setup;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Str;

class CreateStaticFragmentCommand extends Command
{
    protected $signature = 'chief:fragment
                        {--force : overwrite existing class if it already exists}';

    protected $description = 'Generate a new chief fragment';

    private FileManipulation $fileManipulation;
    private SetupConfig $config;

    public function __construct(FileManipulation $fileManipulation, SetupConfig $config)
    {
        parent::__construct();

        $this->fileManipulation = $fileManipulation;

        $this->config = $config;
    }

    public function handle()
    {
        $this->fileManipulation->setOutput($this->output);

        $name = null;
        $path = null;
        $namespace = null;

        while (! $name) {
            $name = $this->ask('What is the name in singular for your fragment?');
        }

        while (! $path) {
            $path = $this->ask('Where do you want to put this class?', $this->config->path());
        }

        while (! $namespace) {
            $namespace = $this->ask('Which namespace will be used?', $this->config->namespace($path));
        }

        $className = Str::studly($name);
        $namespacedClassName = '\\' . $namespace . '\\' . $className;
        $fullPath = base_path($path) . '/' . $className.'.php';

        $this->fileManipulation->writeFile($fullPath, $this->replacePlaceholders(file_get_contents(__DIR__ .'/stubs/staticFragment.php.stub'), [
            'className' => $className,
            'namespace' => $namespace,
        ]), $this->option('force'));

        $this->fileManipulation->addToMethod(app_path('Providers/AppServiceProvider.php'), 'boot', 'chiefRegister()->fragment('.$namespacedClassName.'::class);');
    }

    protected function writeFrontendView(): void
    {
        $path = $this->viewPath(str_replace('.', '/', 'components.'.$this->getView()).'.blade.php');

        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        if ($this->files->exists($path) && ! $this->option('force')) {
            $this->error('View already exists!');

            return;
        }

        file_put_contents($path, '<div>
    <!-- '.Inspiring::quote().' -->
</div>');
    }
    protected function replacePlaceholders($content, $values): string
    {
        $replacements = [
            '__STUB_NAMESPACE__' => $values['namespace'],
            '__STUB_CLASSNAME__' => $values['className'],
            '__STUB_FIELDS__' => '',
        ];
        // --fields=name:input,online:bool,
        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }
}
