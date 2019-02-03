<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Pages\PageManager;

class ArticlePageManager extends PageManager
{
    public function fields(): Fields
    {
        return parent::fields()->add(
            InputField::make('content')
                ->translatable(['nl', 'en'])
        );
    }
}
