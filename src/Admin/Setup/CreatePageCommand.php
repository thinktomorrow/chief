<?php

namespace Thinktomorrow\Chief\Admin\Setup;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class CreatePageCommand extends Command
{
    protected $signature = 'chief:page
                        {name : required name of the model}
                        {--force : overwrite existing class if it already exists}';

    protected $description = 'Generate a new chief page';

    private FileManipulation $fileManipulation;
    private SetupConfig $config;

    private string $className;

    public function __construct(FileManipulation $fileManipulation, SetupConfig $config)
    {
        parent::__construct();

        $this->fileManipulation = $fileManipulation;

        $this->config = $config;
    }

    public function handle()
    {
        $this->fileManipulation->setOutput($this->output);

        $this->className = Str::studly($this->argument('name'));

        $this->fileManipulation->writeFile(
            $this->config->path($this->className.'.php'),
            $this->replacePlaceholders(file_get_contents(__DIR__ .'/stubs/pageModel.php.stub')),
            $this->option('force')
        );

        $this->fileManipulation->addToMethod(
            app_path('Providers/AppServiceProvider.php'),
            'boot',
            'chiefRegister()->model('.$this->config->namespacedClass($this->className).'::class, \Thinktomorrow\Chief\Managers\Presets\PageManager::class, \'nav\');'
        );
        // If already exists: don't overwrite unless --force
        // model in namespace
        // registration add to AppServiceProvider
        // add frontend viewfile

        // --admin-views: add admin views

//        $this->info('Class ' . $this->className . ' created');
    }

    protected function writeFrontendView(): void
    {

        $path = $this->viewPath(
            str_replace('.', '/', 'components.'.$this->getView()).'.blade.php'
        );

        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        if ($this->files->exists($path) && ! $this->option('force')) {
            $this->error('View already exists!');

            return;
        }

        file_put_contents(
            $path,
            '<div>
    <!-- '.Inspiring::quote().' -->
</div>'
        );
    }
    protected function replacePlaceholders($content): string
    {
        $replacements = [
            '##NAMESPACE##' => $this->config->namespace(),
            '##CLASSNAME##' => $this->className,
            '##FIELDS##' => '// hier komen de fields',
        ];
// --fields=name:input,online:bool,
        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }
}
