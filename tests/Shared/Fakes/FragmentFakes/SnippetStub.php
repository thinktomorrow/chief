<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes;

use Thinktomorrow\Chief\ManagedModels\Fields\Types\FileField;
use Thinktomorrow\Chief\Fragments\Assistants\StaticFragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;

class SnippetStub implements Fragmentable
{
    use StaticFragmentableDefaults;

    public static function managedModelKey(): string
    {
        return 'snippet-stub';
    }

    public function fields(): iterable
    {
        yield InputField::make('title');
        yield FileField::make('thumb')->locales();
    }

    public function getTitle()
    {
        return $this->fragmentModel()->title;
    }
}
