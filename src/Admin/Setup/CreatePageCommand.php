<?php

namespace Thinktomorrow\Chief\Admin\Setup;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class CreatePageCommand extends Command
{
    protected $signature = 'chief:page
                        {--force : overwrite existing class if it already exists}';

    protected $description = 'Generate a new chief page';

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
        $createMigrationFile = false;

        while (! $name) {
            $name = $this->ask('What is the name in singular for your page model?');
        }

        while (! $path) {
            $path = $this->ask('Where do you want to put this class?', $this->config->path());
        }

        while (! $namespace) {
            $namespace = $this->ask('Which namespace will be used?', $this->config->namespace());
        }

        if($this->confirm('Would you like to create a migration file?', true)) {
            $createMigrationFile = true;
        }

        $className = Str::studly($name);
        $namespacedClassName = '\\' . $namespace . '\\' . $className;

        $this->fileManipulation->writeFile(
            $this->config->path($className.'.php'),
            $this->replacePlaceholders(file_get_contents(__DIR__ .'/stubs/pageModel.php.stub'), [
                'className' => $className,
                'namespace' => $namespace,
            ]),
            $this->option('force')
        );

        $this->fileManipulation->addToMethod(
            app_path('Providers/AppServiceProvider.php'),
            'boot',
            'chiefRegister()->model('.$namespacedClassName.'::class, \Thinktomorrow\Chief\Managers\Presets\PageManager::class, \'nav\');'
        );

        if($createMigrationFile) {
            $this->call('chief:page-migration', ['table' => Str::snake(Str::plural($className))]);
        }

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
    protected function replacePlaceholders($content, $values): string
    {
        $replacements = [
            '__STUB_NAMESPACE__' => $values['namespace'],
            '__STUB_CLASSNAME__' => $values['className'],
            '__STUB_FIELDS__' => '// hier komen de fields',
        ];
// --fields=name:input,online:bool,
        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }
}
