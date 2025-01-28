<?php

namespace Thinktomorrow\Chief\Fragments\Tests;

use Thinktomorrow\Chief\Fragments\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class ContextTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_can_get_context_per_locale()
    {

    }

    public function test_it_can_get_fragments_for_given_locale()
    {
        $owner = $this->setupAndCreateArticle();
        $this->setupAndCreateSnippet($owner, 1);
        $this->createAsFragment(new SnippetStub(), $owner, 2, ['title_trans' => ['nl' => 'foobar', 'en' => 'foobaz']]);

        $this->assertRenderedFragments($owner, "THIS IS SNIPPET STUB VIEW \nTHIS IS SNIPPET STUB VIEW foobar\n");

        app(FragmentRepository::class)->getByOwner($owner);
    }

    public function test_it_can_render_fragments_for_given_locale()
    {
        $owner = $this->setupAndCreateArticle();
        $this->setupAndCreateSnippet($owner, 1);
        $this->createAsFragment(new SnippetStub(), $owner, 2, ['title_trans' => ['nl' => 'foobar', 'en' => 'foobaz']]);

        $this->assertRenderedFragments($owner, "THIS IS SNIPPET STUB VIEW \nTHIS IS SNIPPET STUB VIEW foobar\n");

        app(FragmentRepository::class)->getByOwner($owner);
    }
}
