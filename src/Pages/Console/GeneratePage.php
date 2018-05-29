<?php

namespace Thinktomorrow\Chief\Pages\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\Common\Traits\Publishable;
use Thinktomorrow\Chief\Common\Traits\Sortable;

class GeneratePage extends BaseCommand
{
    protected $signature = 'chief:page 
                            {name : the name for the page model in singular.}
                            {--force : Overwrite any existing files.}';

    protected $description = 'Generate a new page model and associated controller, routes and views.';

    private $filesystem;
    private $settings;

    private $dirs;
    private $files;

    private $chosenTraits = [];

    private $singular;
    private $plural;

    public function __construct(Filesystem $filesystem, array $settings = [])
    {
        parent::__construct();

        $this->filesystem = $filesystem;
        $this->settings = $settings;

        // Set required paths
        $this->dirs = ['base' => $this->settings['base_path'] ?? base_path()];
        $this->dirs['model'] = $this->settings['model_path'] ?? $this->dirs['base'].'/src';
        $this->dirs['views'] = $this->settings['views_path'] ?? $this->dirs['base'].'/resources/views';
        $this->dirs['controller'] = $this->settings['controller_path'] ?? $this->dirs['base'].'/app/Http/Controllers';

        $this->files['routes'] = $this->settings['routes_file'] ?? $this->dirs['base'].'/routes/web.php';
    }

    public function handle()
    {
        $this->singular = $this->argument('name');
        $this->plural = Str::plural($this->singular);

        $this->promptForModelTraits();
        // anticipate model name and check if file already exists
        // Yes: ask to override or keep exisint (default)
        // -> Generate model

        $this->publishModel();
//        $this->publishController();
    }

    private function publishModel()
    {
        $this->publishFile(
            __DIR__ . '/stubs/model.php.stub',
            $to = $this->dirs['model'] . '/' . ucfirst($this->plural) . '/' . ucfirst($this->singular) . '.php',
            'model'
        );
    }

    private function publishController()
    {
        $this->publishFile(
            __DIR__ . '/stubs/controller.php.stub',
            $to = $this->dirs['controller'] . '/' . ucfirst($this->plural) . '/' . ucfirst($this->singular) . '.php',
            'controller'
        );
    }

    /**
     * Prompt for which provider or tag to publish.
     *
     * @return void
     */
    protected function promptForModelTraits()
    {
        $choice = null;

        while (!in_array($choice, ['q'])) {
            $choice = $this->choice(
                "Which model options would you like to set up?",
                $choices = $this->modelTraits(),
                'q' // Default is to just continue without traits
            );

            if (!in_array($choice, ['q'])) {
                $this->chooseTrait($choices[$choice]);
            }
        }
    }

    protected function chooseTrait(string $trait)
    {
        if (in_array($trait, $this->chosenTraits)) {
            return;
        }

        $this->chosenTraits[] = $trait;
    }

    private function modelTraits()
    {
        return [
            Publishable::class,
            Sortable::class,
            'q' => 'Proceed.',
        ];
    }

    /**
     * Publish the file to the given path.
     *
     * @param  string $from
     * @param  string $to
     * @return void
     */
    protected function publishFile($from, $to, $type)
    {
        if ($this->filesystem->exists($to) && !$this->option('force')) {
            if (!$this->confirm('File [' . $to . '] already exists? Overwrite this file?')) {
                return;
            }
        }

        $this->createParentDirectory(dirname($to));

        $this->filesystem->put($to, $this->replacePlaceholders(file_get_contents($from)));

        $this->status($from, $to, $type);
    }

    /**
     * Create the directory to house the published files if needed.
     *
     * @param  string $directory
     * @return void
     */
    protected function createParentDirectory($directory)
    {
        if (!$this->filesystem->isDirectory($directory)) {
            $this->filesystem->makeDirectory($directory, 0755, true);
        }
    }

    /**
     * Write a status message to the console.
     *
     * @param  string $from
     * @param  string $to
     * @param  string $type
     * @return void
     */
    protected function status($from, $to, $type)
    {
        $from = str_replace($this->dirs['base'], '', realpath($from));

        $to = str_replace($this->dirs['base'], '', realpath($to));

        $this->line('<info>Copied ' . $type . '</info> <comment>[' . $from . ']</comment> <info>To</info> <comment>[' . $to . ']</comment>');
    }

    protected function replacePlaceholders($content)
    {
        $replacements = [
            '##NAMESPACE##' => $this->guessNamespace(), // TODO: how to determine proper namespace?
            '##CLASSNAME##' => ucfirst($this->singular),
            '##TABLENAME##' => strtolower($this->plural),
            '##IMPORTS##'   => $this->generateImportStatements(),
            '##TRAITS##'    => $this->generateTraitStatements(),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    private function generateImportStatements(): string
    {
        return collect(['Illuminate\Database\Eloquent\Model'])
            ->map(function ($statement) {
                return 'use ' . $statement . ";\n    ";
            })->implode('');
    }

    private function generateTraitStatements(): string
    {
        return collect($this->chosenTraits)
            ->map(function ($statement) {
                return 'use ' . $statement . ";\n    ";
            })->implode('');
    }

    private function guessNamespace()
    {
        if (isset($this->settings['namespace'])) {
            return $this->settings['namespace'];
        }

        // We make an estimated guess based on the project name. At Think Tomorrow, we
        // have a src folder which is PSR-4 namespaced by the project name itself.
        return ucfirst(config('thinktomorrow.chief.name', 'App')).'\\'. ucfirst($this->plural);
    }
}
