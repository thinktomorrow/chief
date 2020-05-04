<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Thinktomorrow\Chief\Management\AbstractManager;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\Types\PageField;
use Thinktomorrow\Chief\Management\Manager;

class ManagerFakeWithPageField extends AbstractManager implements Manager
{
    public function fields(): Fields
    {
        return parent::fields()->add(
            PageField::make('buttonlink')
                ->exclude($this->modelInstance())
                ->options()
        );
    }
}
