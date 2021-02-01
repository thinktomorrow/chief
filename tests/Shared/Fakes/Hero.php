<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Fragments\Assistants\StaticFragmentableDefaults;

class Hero implements Fragmentable
{
    use StaticFragmentableDefaults;

    public function renderAdminFragment($owner, $loop, $fragments)
    {
        return 'hero-admin-fragment';
    }

    public function renderFragment($owner, $loop, $fragments, $viewData): string
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
