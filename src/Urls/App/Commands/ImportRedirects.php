<?php

namespace Thinktomorrow\Chief\Urls\App\Commands;

use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\App\Console\ReadsCsv;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\CreateRedirectFromSlugs;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\RedirectApplication;
use Thinktomorrow\Chief\Urls\Exceptions\RedirectUrlAlreadyExists;
use Thinktomorrow\Chief\Urls\Exceptions\UrlRecordNotFound;

class ImportRedirects extends BaseCommand
{
    use ReadsCsv;

    protected $signature = 'chief:import-redirects {file}';

    protected $description = 'Import a list of redirects';

    private RedirectApplication $redirectApplication;

    public function __construct(RedirectApplication $redirectApplication)
    {
        parent::__construct();
        $this->redirectApplication = $redirectApplication;
    }

    public function handle(): void
    {
        // CSV should consist of: locale - redirect url - target -url
        $this->loop($this->argument('file'), function ($row) {
            try {
                $this->redirectApplication->createRedirectFromSlugs(new CreateRedirectFromSlugs(
                    $row[0], $row[1], $row[2]
                ));
            } catch (UrlRecordNotFound $e) {
                $this->warn('No record found for targeturl ['.$row[2].'], locale ['.$row[0].']');

                return;
            } catch (RedirectUrlAlreadyExists $e) {
                $this->warn('Redirect url already exists as record ['.$row[1].'], locale ['.$row[0].']');

                return;
            }
            $this->info('Added '.$row[0].' redirect: '.$row[1].' -> '.$row[2]);
        });

        $this->info('Finished adding redirects');
    }

    protected function createAlternateLocales(array $locales, $locale): array
    {
        if (($key = array_search($locale, $locales)) !== false) {
            unset($locales[$key]);
        }

        return $locales;
    }
}
