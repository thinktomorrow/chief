<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes;

use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\FileField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;

class SnippetStub implements Fragmentable, FragmentsOwner
{
    use FragmentableDefaults;
    use OwningFragments;

    public static function managedModelKey(): string
    {
        return 'snippet-stub';
    }

    public function fields(): iterable
    {
        yield InputField::make('title');
        yield InputField::make('title_trans')->locales();
        yield FileField::make('thumb')->locales();
    }

    public function getTitle()
    {
        return $this->fragmentModel()->title;
    }
}
