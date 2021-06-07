<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentAssistant;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\FragmentableStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\OwnerStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;

class RenderingFragmentsTest extends ChiefTestCase
{
    /** @var FragmentRepository */
    private $fragmentRepo;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function fragments_can_be_rendered()
    {
        $owner = $this->setupAndCreateArticle();
        $this->setupAndCreateSnippet($owner, 1);
        $this->setupAndCreateSnippet($owner, 2);

        $this->assertRenderedFragments($owner, "THIS IS SNIPPET STUB VIEW\nTHIS IS SNIPPET STUB VIEW\n" );
    }

    /** @test */
    public function no_fragments_render_an_empty_string()
    {
        $owner = $this->setupAndCreateArticle();

        $this->assertRenderedFragments($owner, '');
    }
}
