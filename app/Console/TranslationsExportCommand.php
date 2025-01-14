<?php

namespace Thinktomorrow\Chief\App\Console;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Plugins\TranslationsExport\Document\TranslationsExportDocument;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class TranslationsExportCommand extends BaseCommand
{
    protected $signature = 'chief:translations-export
                                    {locale : the origin locale}
                                    {--target= : the locales for which you want to provde a column}';
    protected $description = 'Export page text content for translations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $locales = config('chief.locales');
        $locale = $this->argument('locale');

        if (! in_array($locale, $locales)) {
            throw new \InvalidArgumentException('Passed locale ' . $locale .' is not found as Chief locale. Available locales are ' . implode(',', $locales));
        }

        $targetLocales = $locales;
        unset($targetLocales[array_search($locale, $targetLocales)]);

        if ($this->option('target')) {
            $targetLocales = explode(',', $this->option('target'));
        }

        $models = $this->getAllModels($locale);

        $models = $models->random(1);

        (new TranslationsExportDocument($models, $locale, $targetLocales))
            ->store('test.xlsx');

        $this->info('Finished export. File available at ...');
    }

    private function getAllModels(string $locale): Collection
    {
        return UrlRecord::allOnlineModels($locale)
            // In case the url is not found or present for given locale.
            ->reject(function (Visitable $model) use ($locale) {
                return ! $model->url($locale);
            });
    }
}
