<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export;

use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Squanto\Database\DatabaseLine;

class ExportTextCommand extends BaseCommand
{
    protected $signature = 'chief:export-text';
    protected $description = 'Export static texts';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $models = DatabaseLine::orderBy('key')->get();

        (new ExportTextDocument($models, config('chief.locales')))
            ->store($filepath = 'exports/' . date('Ymd') .'/'.config('app.name') .'-text-'.date('Y-m-d').'.xlsx');

        $this->info('Finished export. File available at: storage/app/' . $filepath);
    }
}
