<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Addons\Repeat\Tests;

use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Addons\Repeat\RepeatField;
use Thinktomorrow\Chief\Forms\Fields\Types\InputField;

trait TestHelpers
{
    private function createRepeatField(?array $values = null, array $locales = []): RepeatField
    {
        return RepeatField::make('foobar', [
            Text::make('title')->locales($locales),
            Text::make('content')->locales($locales),
        ])->valueResolver(function () use ($values) {
            if (is_array($values)) {
                return $values;
            }

            // Default values
            return [
                ['title' => 'first title', 'content' => 'first content'],
                ['title' => 'second title', 'content' => 'second content'],
            ];
        });
    }
}
