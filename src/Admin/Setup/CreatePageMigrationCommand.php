<?php

namespace Thinktomorrow\Chief\Admin\Setup;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class CreatePageMigrationCommand extends Command
{
    protected $signature = 'chief:page-migration
                        { table : name of the pages table }
                        {--force : overwrite existing class if it already exists}';

    protected $description = 'Generate a new chief page migration';

    private FileManipulation $fileManipulation;

    public function __construct(FileManipulation $fileManipulation)
    {
        parent::__construct();

        $this->fileManipulation = $fileManipulation;
    }

    public function handle()
    {
        $this->fileManipulation->setOutput($this->output);

        $tableName = $this->argument('table');
        $className = 'Create' . Str::studly($tableName) . 'Table';

        $this->fileManipulation->writeFile(
            database_path('migrations/'. date('Y_m_d_His') .'_' . Str::snake($className, '_').'.php'),
            $this->replacePlaceholders(file_get_contents(__DIR__ .'/stubs/pageModelMigration.php.stub'), [
                'className' => $className,
                'tableName' => $tableName,
            ]),
            $this->option('force')
        );
    }

    protected function replacePlaceholders($content, $values): string
    {
        $replacements = [
            '__STUB_CLASSNAME__' => $values['className'],
            '__STUB_TABLENAME__' => $values['tableName'],
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }
}
