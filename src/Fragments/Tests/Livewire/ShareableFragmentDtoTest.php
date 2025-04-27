<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Livewire;

use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;

class ShareableFragmentDtoTest extends ChiefTestCase
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

    public function test_it_can_get_shareablefragmentdto_collection()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id);

        // Attach fragment to two contexts
        $owner2 = ArticlePage::create([]);
        $context2 = FragmentTestHelpers::createContext($owner2);
        FragmentTestHelpers::attachFragment($context2->id, $fragment->getFragmentId());

        $sharedFragmentDtos = $this->composer->getSharedFragmentDtos($fragment->getFragmentId(), $this->owner);

        $this->assertCount(2, $sharedFragmentDtos);

        $ownerResource = app(Registry::class)->findResourceByModel(ArticlePage::class);

        $this->assertEquals($context->id, $sharedFragmentDtos->first()->contextId);
        $this->assertEquals($context2->id, $sharedFragmentDtos->last()->contextId);
        $this->assertEquals($context->title, $sharedFragmentDtos->first()->contextLabel);
        $this->assertEquals($context2->title, $sharedFragmentDtos->last()->contextLabel);
        $this->assertEquals($this->owner->modelReference()->get(), $sharedFragmentDtos->first()->ownerReference);
        $this->assertEquals($owner2->modelReference()->get(), $sharedFragmentDtos->last()->ownerReference);
        $this->assertEquals('http://localhost/admin/article_page/'.$this->owner->getKey().'/edit', $sharedFragmentDtos->first()->ownerAdminUrl);
        $this->assertEquals('http://localhost/admin/article_page/'.$owner2->getKey().'/edit', $sharedFragmentDtos->last()->ownerAdminUrl);
        $this->assertEquals($ownerResource->getPageTitle($this->owner), $sharedFragmentDtos->first()->ownerLabel);
        $this->assertEquals($ownerResource->getPageTitle($owner2), $sharedFragmentDtos->last()->ownerLabel);
    }
}
