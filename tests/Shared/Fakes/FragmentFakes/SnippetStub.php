<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes;

use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Fragments\Assistants\ForwardFragmentProperties;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Forms\Fields\Types\FileField;
use Thinktomorrow\Chief\Forms\Fields\Types\InputField;

class SnippetStub implements Fragmentable, FragmentsOwner
{
    use FragmentableDefaults;
    use ForwardFragmentProperties;
    use OwningFragments;

    public static function managedModelKey(): string
    {
        return 'snippet-stub';
    }

    public function fields(): iterable
    {
        yield Text::make('title');
        yield Text::make('title_trans')->locales();
//        yield FileField::make('thumb')->locales();
    }

    public function getTitle()
    {
        return $this->fragmentModel()->title;
    }
}
