<?php

namespace Thinktomorrow\Chief\App\Console;

use Thinktomorrow\Chief\Plugins\ContentExport\CrawlSite;

class ContentExportCommand extends BaseCommand
{
    protected $signature = 'chief:content-export {locale}';
    protected $description = 'Export page content';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        // test
        app(CrawlSite::class)->handle('nl');


        $this->info('Finished export. File available at ...');
    }
}
