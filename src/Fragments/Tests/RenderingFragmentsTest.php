<?php

namespace Thinktomorrow\Chief\Fragments\Tests;

use Thinktomorrow\Chief\Fragments\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class RenderingFragmentsTest extends ChiefTestCase
{
    /** @var FragmentRepository */
    private $fragmentRepo;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_fragments_can_be_rendered()
    {
        $owner = $this->setupAndCreateArticle();
        $this->setupAndCreateSnippet($owner, 1);
        $this->createAsFragment(new SnippetStub(), $owner, 2, ['title_trans' => ['nl' => 'foobar']]);

        $this->assertRenderedFragments($owner, "THIS IS SNIPPET STUB VIEW \nTHIS IS SNIPPET STUB VIEW foobar\n");
    }

    public function test_no_fragments_render_an_empty_string()
    {
        $owner = $this->setupAndCreateArticle();

        $this->assertRenderedFragments($owner, '');
    }

    public function test_fragments_can_be_rendered_with_fallback_locale()
    {
        $owner = $this->setupAndCreateArticle();

        $this->setupAndCreateSnippet($owner, 1, true, ['title_trans' => ['en' => 'foobar EN']]);

        $this->assertRenderedFragments($owner, "THIS IS SNIPPET STUB VIEW foobar EN\n");
    }

    /** @test */
    public function fragments_can_be_rendered_by_locale()
    {
        $owner = $this->setupAndCreateArticle();
        $this->setupAndCreateSnippet($owner, 1);
        $this->createAsFragment(new SnippetStub(), $owner, 2, ['title_trans' => ['nl' => 'foobar']]);

        $this->assertRenderedFragments($owner, "THIS IS SNIPPET STUB VIEW \nTHIS IS SNIPPET STUB VIEW foobar\n");
    }
}
