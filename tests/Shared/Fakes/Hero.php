<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Types\InputField;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Fragmentable;

class Hero implements Fragmentable
{
    use FragmentableDefaults;

    public function renderAdminFragment($owner, $loop, $viewData = []): string
    {
        return 'hero-admin-fragment';
    }

    public function renderFragment($owner, $loop, $viewData = []): string
    {
        return 'hero-fragment';
    }

    public function fields(): Fields
    {
        return Fields::make([
            InputField::make('title'),
        ]);
    }

    public function getTitle()
    {
        return $this->fragmentModel()->title;
    }
}
