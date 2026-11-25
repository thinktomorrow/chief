<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Tests\Stubs;

use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class MoveContainerStub extends SnippetStub
{
    public static function resourceKey(): string
    {
        return 'move-container-stub';
    }

    public function allowedFragments(): array
    {
        return [self::class, SnippetStub::class];
    }
}
