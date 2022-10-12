<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table;

use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Table\Elements\TableCell;
use Thinktomorrow\Chief\Table\Elements\TableCellLink;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

trait TableResourceDefault
{
    public function getTableColumns(): iterable
    {
        return [];
    }

    public function getTableRowId($model): string
    {
        return (string) $model->{$model->getKeyName()};
    }

    public function getTableRow($model, $manager): iterable
    {
        yield TableCellLink::make($this->getPageTitle($model))
            ->url($manager->route('edit', $model));

        if($model instanceof StatefulContract) {
            foreach($model->getStateKeys() as $stateKey) {
                yield TableCell::make($model->getStateConfig($stateKey)->getStateLabel($model));
            }
        }
    }

    public function getTableActions(Manager $manager): iterable
    {
        return [];
    }
}
