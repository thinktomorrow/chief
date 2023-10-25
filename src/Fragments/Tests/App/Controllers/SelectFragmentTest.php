<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Controllers;

use function route;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextRepository;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class SelectFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private Quote $fragment;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
        $this->fragment = $this->setupAndCreateQuote($this->owner);
    }

    public function test_admin_can_view_the_fragment_select_new()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');

        $this->asAdmin()
            ->get(route('chief::fragments.new', [$context->id]))
            ->assertStatus(200);
    }

    public function test_admin_can_view_the_fragment_select_existing()
    {
        $owner = ArticlePage::create();
        $context = ContextModel::create(['owner_type' => $owner->getMorphClass(), 'owner_id' => $owner->id, 'locale' => 'nl']);

        $owner2 = ArticlePage::create();
        $context2 = ContextModel::create(['owner_type' => $owner2->getMorphClass(), 'owner_id' => $owner2->id, 'locale' => 'nl']);

        $fragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);
        app(AttachFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1);

        $this->asAdmin()
            ->get(route('chief::fragments.existing', [$context->id]))
            ->assertStatus(200);
    }
}
