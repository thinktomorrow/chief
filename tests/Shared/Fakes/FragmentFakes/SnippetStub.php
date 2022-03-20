<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes;

use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\NotFoundViewKey;
use Thinktomorrow\Chief\Fragments\Assistants\ForwardFragmentProperties;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;

class SnippetStub implements Fragmentable, FragmentsOwner
{
    use FragmentableDefaults;
    use ForwardFragmentProperties;
    use OwningFragments;

    public static function resourceKey(): string
    {
        return 'snippet-stub';
    }

    public function fields($model): iterable
    {
        yield Text::make('title');
        yield Text::make('title_trans')->locales();
        yield File::make('thumb')->locales();
    }

    public function getTitle()
    {
        return $this->fragmentModel()->title;
    }

    public function viewKey(): string
    {
        return 'snippet_stub';
    }
}
