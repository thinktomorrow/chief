<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export;

use Illuminate\Database\Eloquent\Collection;
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
        $models = MenuItem::query()
            ->where('status', 'online')
            ->orderBy('menu_type')
            ->get();

        $models = $this->sortModels($models);

        (new ExportMenuDocument($models, config('chief.locales')))
            ->store($filepath = 'exports/' . date('Ymd-His') .'/'.config('app.name') .'-menu-'.date('Y-m-d').'.xlsx');

        $this->info('Finished export. File available at: storage/app/' . $filepath);
    }

    private function sortModels(Collection $models): Collection
    {
        // Sort the models by menu type, parent and order
        return $models->map(function ($item) use ($models) {
            $item->grouped_order = $item->order . '000';
            if ($item->parent_id) {
                $item->grouped_order = $models->find($item->parent_id)->order . str_pad($item->order + 1, 3, '0', STR_PAD_LEFT);
            }

            return $item;
        })
            ->sortBy(function ($item) {
                return $item->menu_type . '-' . $item->grouped_order;
            });
    }
}
