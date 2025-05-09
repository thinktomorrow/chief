<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Queries;

use Thinktomorrow\Chief\Fragments\ActiveContextId;
use Thinktomorrow\Chief\Fragments\App\Queries\GetFragments;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Fragments\Tests\Stubs\RootFragmentStub;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class GetFragmentsTest extends ChiefTestCase
{
    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Hero::class);
    }

    protected function tearDown(): void
    {
        ActiveContextId::clear();

        parent::tearDown();
    }

    public function test_it_can_render_fragments()
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id, null, 0, ['custom' => 'foobar']);

        $fragments = app(GetFragments::class)->get($context->id);

        $this->assertCount(1, $fragments);
        $this->assertEquals("THIS IS QUOTE FRAGMENT\n", $fragments->first()->render());
    }

    public function test_it_can_render_fragments_via_function()
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id, null, 0, ['custom' => 'foobar']);

        ActiveContextId::set($context->id);
        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\n    THIS IS QUOTE FRAGMENT\n\n", $this->owner->renderView()->render());
    }

    public function test_it_can_render_child_fragments()
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        $rootFragment = FragmentTestHelpers::createAndAttachFragment(RootFragmentStub::class, $context->id);
        FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id, $rootFragment->getFragmentId(), 0, ['custom' => 'foobar']);

        ActiveContextId::set($context->id);
        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\n    THIS IS ROOT FRAGMENT\n    THIS IS QUOTE FRAGMENT\n\n\n", $this->owner->renderView()->render());
    }

    public function test_it_returns_empty_collection_when_no_fragments_are_found()
    {
        $context = FragmentTestHelpers::createContext($this->owner);

        $fragments = app(GetFragments::class)->get($context->id);

        $this->assertCount(0, $fragments);
    }

    public function test_fragments_can_be_rendered_by_locale()
    {
        config()->set('chief.sites', [
            ['locale' => 'en', 'fallback_locale' => null],
            ['locale' => 'nl', 'fallback_locale' => 'en', 'primary' => true], // First is primary
            ['locale' => 'fr', 'fallback_locale' => 'nl'],
        ]);

        $context = FragmentTestHelpers::createContext($this->owner);

        FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, null, 0, ['title_trans' => ['nl' => 'foobar NL', 'en' => 'foobar EN']]);

        // Must set context for fragment rendering
        ActiveContextId::set($context->id);

        app()->setLocale('nl');

        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\n    THIS IS SNIPPET STUB VIEW foobar NL\n\n", $this->owner->renderView()->render());
    }

    public function test_fragments_can_be_rendered_with_fallback_locale()
    {
        config()->set('chief.sites', [
            ['locale' => 'en', 'fallback_locale' => null],
            ['locale' => 'nl', 'fallback_locale' => 'en', 'primary' => true], // First is primary
            ['locale' => 'fr', 'fallback_locale' => 'nl'],
        ]);

        $context = FragmentTestHelpers::createContext($this->owner);

        FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, null, 0, ['title_trans' => ['en' => 'foobar EN']]);

        // Must set context for fragment rendering
        ActiveContextId::set($context->id);

        app()->setLocale('fr');

        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\n    THIS IS SNIPPET STUB VIEW foobar EN\n\n", $this->owner->renderView()->render());
    }

    public function test_fragments_can_be_rendered_without_locale_value_if_no_fallback_active()
    {
        config()->set('chief.sites', [
            ['locale' => 'en', 'fallback_locale' => null],
            ['locale' => 'nl', 'fallback_locale' => null], // First is primary
        ]);

        $context = FragmentTestHelpers::createContext($this->owner);

        FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, null, 0, ['title_trans' => ['en' => 'foobar EN']]);

        // Must set context for fragment rendering
        ActiveContextId::set($context->id);

        app()->setLocale('nl');

        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\n    THIS IS SNIPPET STUB VIEW \n\n", $this->owner->renderView()->render());
    }
}
