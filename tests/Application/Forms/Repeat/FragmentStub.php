<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Forms\Repeat;

use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Forms\Layouts\Grid;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\Fragments\Bookmark\BookmarkDefaults;
use Thinktomorrow\Chief\Fragments\Bookmark\HasBookmark;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;

class FragmentStub implements Fragmentable, FragmentsOwner, HasBookmark
{
    use FragmentableDefaults;
    use OwningFragments;
    use BookmarkDefaults;

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

    public static function resourceKey(): string
    {
        return 'fragment-stub';
    }

    public function fields($model): iterable
    {
        yield Form::make('repeat_form')->items([
            Repeat::make('repeat_values')->items([
                Text::make('first'),
                Text::make('second'),
                Repeat::make('nested')->items([
                    Text::make('nested-first'),
                    Text::make('nested-second'),
                ]),
                Grid::make('grid')->items([
                    Text::make('grid-first'),
                    Text::make('grid-second'),
                ]),
            ]),
        ]);
    }

    public function viewKey(): string
    {
        return 'fragmentable_stub';
    }
}
