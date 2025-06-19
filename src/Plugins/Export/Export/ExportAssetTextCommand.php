<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export;

use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\App\Console\BaseCommand;
use Thinktomorrow\Chief\Sites\ChiefSites;

class ExportAssetTextCommand extends BaseCommand
{
    protected $signature = 'chief:export-asset-text {--hive : AI generate any missing alt texts}';

    protected $description = 'Export asset filename and image alt texts';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $models = Asset::all();

        // Only images are relevant for alt text export
        $models = $models->filter(function ($model) {
            return $model->isImage();
        });

        (new ExportAssetTextDocument($models, ChiefSites::locales(), $this->option('hive')))
            ->store($filepath = 'exports/'.date('Ymd').'/'.config('app.name').'-alt-'.date('Y-m-d').'.xlsx');

        $this->info('Finished export. File available at: storage/app/'.$filepath);
    }
}
