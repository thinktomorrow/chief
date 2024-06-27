<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export;

use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\Site\Menu\MenuItem;

class ExportMenuCommand extends BaseCommand
{
    protected $signature = 'chief:export-menu';
    protected $description = 'Export menu content';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $models = MenuItem::orderBy('menu_type')->orderBy('order')->get(); // Group by type?

        (new ExportMenuDocument($models, config('chief.locales')))
            ->store($filepath = config('app.name') .'-menu-'.date('Y-m-d').'.xlsx');

        $this->info('Finished export. File available at: storage/app/' . $filepath);
    }
}
