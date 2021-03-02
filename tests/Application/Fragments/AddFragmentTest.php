<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class AddFragmentTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function a_fragment_can_be_added()
    {
        $owner = $this->setupAndCreateArticle();
        $owner2 = ArticlePage::create();
        $fragment = $this->setupAndCreateQuote($owner);
        $fragmentManager = $this->manager($fragment);

        $this->asAdmin()->post($fragmentManager->route('fragment-add', $owner2, $fragment));

        $fragments = app(FragmentRepository::class)->getByOwner($owner2);
        $this->assertCount(1, $fragments);
    }

    /** @test */
    public function a_nested_fragment_can_be_added()
    {
        $owner = $this->setupAndCreateArticle();
        $fragmentOwner = $this->setupAndCreateQuote($owner);

        $fragment = $this->addAsFragment(ArticlePage::create(), $owner);
        $fragmentManager = $this->manager($fragment);

        $this->asAdmin()->post($fragmentManager->route('fragment-add', $fragmentOwner, $fragment))
            ->assertStatus(201);

        $fragments = app(FragmentRepository::class)->getByOwner($fragmentOwner->fragmentModel());
        $this->assertCount(1, $fragments);
    }
}
