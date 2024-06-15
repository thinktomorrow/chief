<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes;

use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\Fragments\BaseFragment;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;

class SnippetStub extends BaseFragment implements Fragment, FragmentsOwner
{
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

    public function dynamicLocaleFallback(): ?string
    {
        return 'en';
    }
}
