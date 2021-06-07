<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;

class Hero implements Fragmentable
{
    use FragmentableDefaults;

    public function renderAdminFragment($owner, $loop): string
    {
        return 'hero-admin-fragment';
    }

    public function renderFragment($owner, $loop, $viewData = []): string
    {
        return 'hero-fragment';
    }

    public function fields(): Fields
    {
        return new Fields([
            InputField::make('title'),
        ]);
    }

    public function getTitle()
    {
        return $this->fragmentModel()->title;
    }
}
