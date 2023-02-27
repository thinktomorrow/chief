<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table;

use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Table\Elements\TableColumn;
use Thinktomorrow\Chief\Table\Elements\TableColumnLink;
use Thinktomorrow\Chief\Table\Elements\TableHeader;

trait TableResourceDefault
{
    public function getTableRow(Manager $manager, $model): iterable
    {
        yield TableColumnLink::make('Titel')
            ->value($this->generateDefaultTitleColumnLink($model))
            ->url($manager->route('edit', $model));

        if ($model instanceof StatefulContract) {
            foreach ($model->getStateKeys() as $stateKey) {
                yield TableColumn::make('Status')->value($model->getStateConfig($stateKey)->getStateLabel($model));
            }
        }
    }

    public function getTableActions(Manager $manager): iterable
    {
        return [];
    }

    public function getTableRowId($model): string
    {
        return (string) $model->{$model->getKeyName()};
    }

    public function getTableHeaders(Manager $manager, $firstModel): iterable
    {
        static $headers = null;

        if ($headers) {
            return $headers;
        }

        foreach ($this->getTableRow($manager, $firstModel) as $tableColumn) {
            $headers[] = TableHeader::fromTableColumn($tableColumn);
        }

        return $headers;
    }

    public function displayTableHeaderAsSticky(): bool
    {
        return false;
    }

    public function generateDefaultTitleColumnLink($model): string
    {
        $output = '<span class="inline-flex items-center gap-1">';

        $output .= '<span>'.teaser($this->getPageTitle($model), 50, '...').'</span>';

        if (\Thinktomorrow\Chief\Admin\Settings\Homepage::is($model)) {
            $output .= '<span class="label label-xs label-primary">Homepage</span>';
        }

        $output .= '</span>';


        return $output;
    }
}
