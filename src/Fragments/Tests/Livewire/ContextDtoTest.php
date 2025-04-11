<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Livewire;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Context\ContextDto;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;

class ContextDtoTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private ComposeLivewireDto $composer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Hero::class);

        $this->composer = app(ComposeLivewireDto::class);
    }

    public function test_it_can_get_context_dto()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner, ['nl', 'fr'], ['fr'], 'context title');
        $dto = $this->composer->getContext($this->owner->modelReference(), $context->id);

        $this->assertInstanceOf(ContextDto::class, $dto);
        $this->assertEquals($context->id, $dto->id);
        $this->assertEquals($context->title, $dto->title);
        $this->assertEquals($this->owner->modelReference(), $dto->ownerReference);
        $this->assertEquals('http://localhost/admin/article_page/'.$this->owner->getKey().'/edit', $dto->ownerAdminUrl);
        $this->assertEquals(['nl', 'fr'], $dto->locales);
        $this->assertEquals(['fr'], $dto->activeSites);
    }

    public function test_it_can_get_contexts_by_owner()
    {
        $context1 = FragmentTestHelpers::findOrCreateContext($this->owner);
        $context2 = FragmentTestHelpers::createContext($this->owner);

        $dtos = $this->composer->getContextsByOwner($this->owner->modelReference());

        $this->assertInstanceOf(Collection::class, $dtos);
        $this->assertCount(2, $dtos);
        $this->assertContainsOnlyInstancesOf(ContextDto::class, $dtos);
        $this->assertEqualsCanonicalizing(
            [$context1->id, $context2->id],
            $dtos->map(fn ($dto) => $dto->id)->all()
        );
    }

    public function test_it_can_compose_empty_context_dto()
    {
        $dto = $this->composer->composeEmptyContext($this->owner->modelReference());

        $this->assertInstanceOf(ContextDto::class, $dto);
        $this->assertStringStartsWith('new-', $dto->id);
        $this->assertNull($dto->title);
        $this->assertEquals($this->owner->modelReference()->get(), $dto->ownerReference);
        $this->assertEquals('http://localhost/admin/article_page/'.$this->owner->getKey().'/edit', $dto->ownerAdminUrl);
        $this->assertEquals(['nl', 'fr', 'en'], $dto->locales); // Default all locales
        $this->assertEquals([], $dto->activeSites);
    }
}
