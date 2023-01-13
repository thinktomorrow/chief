<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Resource;

use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\PageResourceDefault;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class PageResourceStub implements PageResource
{
    use PageResourceDefault;

    public static function modelClassName(): string
    {
        return SnippetStub::class;
    }

    public function fields($model): iterable
    {
        return [
            Text::make('first'),
            Text::make('second'),
        ];
    }
}
