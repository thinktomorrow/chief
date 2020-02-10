<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Thinktomorrow\Chief\Fields\Types\DocumentField;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Fields\Types\ImageField;
use Thinktomorrow\Chief\Management\AbstractManager;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Management\Manager;

class ManagerWithPaginationFake extends AbstractManager implements Manager
{
    protected $pageCount = 1;

    public function fields(): Fields
    {
        return parent::fields()->add(
            InputField::make('title'),
            InputField::make('custom'),
            InputField::make('title_trans')->translatable(['nl', 'fr']),
            InputField::make('content_trans')->translatable(['nl', 'fr']),
            ImageField::make('avatar'),
            DocumentField::make('doc')
        );
    }
}
