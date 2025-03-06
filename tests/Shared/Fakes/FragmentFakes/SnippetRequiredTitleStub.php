<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes;

use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Fragments\BaseFragment;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ForwardFragmentProperties;

class SnippetRequiredTitleStub extends BaseFragment implements Fragment
{
    use ForwardFragmentProperties;

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
        return $this->getFragmentModel()->title;
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
