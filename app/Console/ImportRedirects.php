<?php

namespace Thinktomorrow\Chief\App\Console;

use Thinktomorrow\Chief\Site\Redirects\AddRedirect;
use Thinktomorrow\Chief\Site\Sitemap\SitemapXmlFile;
use Thinktomorrow\Chief\Site\Urls\UrlRecordNotFound;
use Thinktomorrow\Chief\Site\Redirects\RedirectUrlAlreadyExists;

class ImportRedirects extends BaseCommand
{
    use ReadsCsv;

    protected $signature = 'chief:import-redirects {file}';
    protected $description = 'Import a list of redirects';
    private AddRedirect $addRedirect;

    public function __construct(AddRedirect $addRedirect)
    {
        parent::__construct();

        $this->addRedirect = $addRedirect;
    }

    public function handle(): void
    {
        // CSV should consist of: locale - redirect url - target -url
        $this->loop($this->argument('file'), function($row){
            try{
                $this->addRedirect->handle($row[0], $row[1], $row[2]);
            } catch(UrlRecordNotFound $e) {
                $this->warn('No record found for targeturl ['.$row[2].'], locale ['.$row[0].']');
                return;
            } catch(RedirectUrlAlreadyExists $e) {
                $this->warn('Redirect url already exists as record ['.$row[1].'], locale ['.$row[0].']');
                return;
            }
            $this->info('Added '.$row[0].' redirect: ' . $row[1] . ' -> ' . $row[2]);
        });

        $this->info('Finished adding redirects');
    }

    /**
     * @param array $locales
     * @param $locale
     * @return array
     */
    protected function createAlternateLocales(array $locales, $locale): array
    {
        if (($key = array_search($locale, $locales)) !== false) {
            unset($locales[$key]);
        }

        return $locales;
    }
}
