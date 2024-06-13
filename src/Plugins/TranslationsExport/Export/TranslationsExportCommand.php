<?php

namespace Thinktomorrow\Chief\Plugins\TranslationsExport\Export;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\Resource;

class TranslationsExportCommand extends BaseCommand
{
    protected $signature = 'chief:trans-export
                                    {model : the model to export translations for}
                                    {--locale= : the origin locale as base for the translations}
                                    {--to= : the locales for which you want to provide a column}';

    protected $description = 'Export model text content for translations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        /** @var Resource $resource */
        $resource = app(Registry::class)->resource($this->argument('model'));

        $locales = config('chief.locales');
        $locale = $this->option('locale') ?: config('chief.locales')[0];

        if(!in_array($locale, $locales)) {
            $this->error('Passed locale "' . $locale .'" is not found as Chief locale. Available locales are: ' . implode(',', $locales));
            return;
        }

        $targetLocales = $this->getTargetLocales($locales, $locale);

        $models = $this->getModels($resource::resourceKey());

        // TEST...
//        $models = $models->random(1);

        (new TranslationsExportDocument($resource, $models, $locale, $targetLocales))
            ->store('test-'.$resource::resourceKey().'.xlsx');
//            ->store('test-'.$resource::resourceKey().'.csv');

        $this->info('Finished export. File available: ' . 'test-'.$resource::resourceKey().'.xlsx');
    }

    private function getModels(string $resourceKey): Collection
    {
        return app(Registry::class)->resource($resourceKey)->all();
    }

//    private function getAllModels(string $locale): Collection
//    {
//        return UrlRecord::allOnlineModels($locale)
//            // In case the url is not found or present for given locale.
//            ->reject(function (Visitable $model) use ($locale) {
//                return ! $model->url($locale);
//            });
//    }

    /**
     * @param mixed $locales
     * @param mixed $locale
     * @return mixed|string[]
     */
    public function getTargetLocales(mixed $locales, mixed $locale): mixed
    {
        $targetLocales = $locales;
        unset($targetLocales[array_search($locale, $targetLocales)]);
        $targetLocales = array_values($targetLocales);

        if ($this->option('to')) {
            $targetLocales = explode(',', $this->option('to'));
        }
        return $targetLocales;
    }
}
