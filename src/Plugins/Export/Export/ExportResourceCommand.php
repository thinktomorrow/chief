<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\Resource;

class ExportResourceCommand extends BaseCommand
{
    protected $signature = 'chief:export-resource
                                    {resource : the resource key of the model to export}
                                    {--locales= : specify the locales, comma separated, if you only want to show translations}';

    protected $description = 'Export model fields for translations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        /** @var Resource $resource */
        $resource = app(Registry::class)->resource($this->argument('resource'));
        $locales = $this->option('locales') ? explode(',', $this->option('locales'))  : config('chief.locales');

        if(count(array_intersect($locales, config('chief.locales'))) !== count($locales)) {
            $this->error('One of passed locales "' . $this->option('locales') .'" is not found as Chief locale. Available locales are: ' . implode(',', config('chief.locales')));

            return;
        }

        $models = $this->getModels($resource::resourceKey());

        (new ExportResourceDocument($resource, $models, $locales, true))
            ->store($filepath = config('app.name') .'-'. $resource::resourceKey().'-'.date('Y-m-d').'.xlsx');

        $this->info('Finished '.$resource::resourceKey().' export. File available at: storage/app/' . $filepath);
    }

    private function getModels(string $resourceKey): Collection
    {
        return app(Registry::class)->resource($resourceKey)::modelClassName()::all();
    }
}
