<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export;

use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Squanto\Database\DatabaseLine;

class ExportTextCommand extends BaseCommand
{
    protected $signature = 'chief:export-text {--hive : AI generate any missing texts for all locales}';

    protected $description = 'Export static texts';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $models = DatabaseLine::orderBy('key')->get();

        (new ExportTextDocument($models, ChiefSites::locales(), $this->option('hive')))
            ->store($filepath = 'exports/'.date('Ymd').'/'.config('app.name').'-text-'.date('Y-m-d').'.xlsx');

        $this->info('Finished export. File available at: storage/app/'.$filepath);
    }
}
