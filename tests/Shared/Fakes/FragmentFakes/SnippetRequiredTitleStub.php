<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes;

use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Fragments\Assistants\ForwardFragmentProperties;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;

class SnippetRequiredTitleStub implements Fragment, FragmentsOwner
{
    use FragmentableDefaults;
    use ForwardFragmentProperties;
    use OwningFragments;

    public static function resourceKey(): string
    {
        return 'snippet-required-stub';
    }

    public function fields($model): iterable
    {
        yield Text::make('title_trans')->required()->locales();
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
