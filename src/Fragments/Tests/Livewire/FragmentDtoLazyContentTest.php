<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Tests\Livewire;

use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Fragment\FragmentDto;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class FragmentDtoLazyContentTest extends ChiefTestCase
{
    public function test_it_can_skip_preview_content_and_fields_when_requested(): void
    {
        $owner = $this->setupAndCreateArticle();

        [$context, $fragment] = FragmentTestHelpers::createContextAndAttachFragment(
            $owner,
            Quote::class,
            null,
            0,
            ['custom' => 'foobar'],
        );

        $contextDto = app(ComposeLivewireDto::class)->getContext($owner->modelReference(), $context->id);

        $fragmentInContext = app(FragmentRepository::class)->findInContext($fragment->getFragmentId(), $context->id);

        $fragmentDto = FragmentDto::fromFragment($fragmentInContext, $contextDto, $owner, false, false);

        $this->assertFalse($fragmentDto->contentLoaded);
        $this->assertSame('', $fragmentDto->content);
        $this->assertCount(0, $fragmentDto->fields);
    }

    public function test_it_can_serialize_loaded_preview_content_state(): void
    {
        $owner = $this->setupAndCreateArticle();

        [$context, $fragment] = FragmentTestHelpers::createContextAndAttachFragment(
            $owner,
            Quote::class,
            null,
            0,
            ['custom' => 'foobar'],
        );

        $contextDto = app(ComposeLivewireDto::class)->getContext($owner->modelReference(), $context->id);

        $fragmentInContext = app(FragmentRepository::class)->findInContext($fragment->getFragmentId(), $context->id);

        $fragmentDto = FragmentDto::fromFragment($fragmentInContext, $contextDto, $owner, true, false);

        $this->assertTrue($fragmentDto->contentLoaded);
        $this->assertIsString($fragmentDto->content);

        $restored = FragmentDto::fromLivewire($fragmentDto->toLivewire());

        $this->assertTrue($restored->contentLoaded);
        $this->assertSame($fragmentDto->content, $restored->content);
        $this->assertCount(0, $restored->fields);
    }
}
