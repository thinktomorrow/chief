<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Addons\Repeat\Tests;

use Thinktomorrow\Chief\Addons\Repeat\RepeatField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class PageStub extends ArticlePage
{
//    public $dynamicKeys = ['authors'];

    public function fields(): iterable
    {
        yield RepeatField::make('authors', [
            InputField::make('name'),
            InputField::make('title')->locales(['nl', 'en']),
        ]);
    }
}
