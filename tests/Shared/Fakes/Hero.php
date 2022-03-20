<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Thinktomorrow\Chief\Forms\Fields;
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

    public function fields($model): Fields
    {
        return Fields::make([
            Fields\Text::make('title'),
        ]);
    }

    public function getTitle()
    {
        return $this->fragmentModel()->title;
    }

    public function viewKey(): string
    {
        return 'hero';
    }
}
