<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Addons\Repeat\tests;

use Thinktomorrow\Chief\Addons\Repeat\RepeatField;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;

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
