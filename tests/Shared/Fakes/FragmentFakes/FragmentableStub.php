<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes;

use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Types\InputField;

class FragmentableStub implements Fragmentable, FragmentsOwner
{
    use FragmentableDefaults;
    use OwningFragments;

    public function renderAdminFragment($owner, $loop, $viewData = []): string
    {
        return '';
    }

    public function renderFragment($owner, $loop, $viewData = []): string
    {
        return 'fragment-stub-'.$this->id.' ';
    }

    public function allowedFragments(): array
    {
        return [];
    }

    public static function managedModelKey(): string
    {
        return 'fragment-stub';
    }

    public function fields(): Fields
    {
        return Fields::make([
            InputField::make('title')->tag('create'),
        ]);
    }
}
