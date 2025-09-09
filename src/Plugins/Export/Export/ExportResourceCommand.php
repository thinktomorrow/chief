<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Sites\ChiefSites;

class ExportResourceCommand extends BaseCommand
{
    protected $signature = 'chief:export-resource
                                    {resource : the resource key of the model to export}
                                    {--include-static : also add the non-localized text as separate column}
                                    {--locales= : specify the locales, comma separated, if you only want to show translations}
                                    {--hive : AI generate any missing texts for all locales}';

    protected $description = 'Export model fields for translations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        /** @var resource $resource */
        $resource = app(Registry::class)->resource($this->argument('resource'));
        $locales = $this->option('locales') ? explode(',', $this->option('locales')) : ChiefSites::locales();

        if (count(array_intersect($locales, ChiefSites::locales())) !== count($locales)) {
            $this->error('One of passed locales "'.$this->option('locales').'" is not found as Chief locale. Available locales are: '.implode(',', ChiefSites::locales()));

            return;
        }

        $models = $this->getModels($resource::resourceKey());

        $this->info('Starting '.$resource::resourceKey().' export of '.$models->count().' items...');

        (new ExportResourceDocument($resource, $models, $locales, ! $this->option('include-static'), $this->option('hive')))
            ->setOutput($this->output)
            ->store($filepath = 'exports/'.date('Ymd').'/'.config('app.name').'-'.$resource::resourceKey().'-'.date('Y-m-d').'.xlsx');

        $this->info('Finished '.$resource::resourceKey().' export. File available at: storage/app/'.$filepath);
    }

    private function getModels(string $resourceKey): Collection
    {
        return app(Registry::class)->resource($resourceKey)::modelClassName()::all();
    }
}
