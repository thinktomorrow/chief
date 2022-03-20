<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Resource;

use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Resource\ResourceDefault;

class ResourceStub implements Resource
{
    use ResourceDefault;

    public static function modelClassName(): string
    {
        return static::class;
    }

    public function fields($model): iterable
    {
        return [
            Text::make('first'),
            Text::make('second'),
        ];
    }
}
