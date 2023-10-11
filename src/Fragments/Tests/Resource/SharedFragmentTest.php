<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Resource;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class SharedFragmentTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class);
        chiefRegister()->fragment(Quote::class);
    }

    public function test_a_fragment_is_default_not_shared()
    {
        $owner = ArticlePage::create();
        $context = ContextModel::create(['owner_type' => $owner->getMorphClass(), 'owner_id' => $owner->id, 'locale' => 'nl']);

        $fragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);

        $this->assertFalse($fragment->fragmentModel()->fresh()->isShared());
    }

    public function test_a_fragment_is_shared_when_attached_to_contexts_belonging_to_different_owners()
    {
        $owner = ArticlePage::create();
        $owner2 = ArticlePage::create();
        $context = ContextModel::create(['owner_type' => $owner->getMorphClass(), 'owner_id' => $owner->id, 'locale' => 'nl']);
        $context2 = ContextModel::create(['owner_type' => $owner2->getMorphClass(), 'owner_id' => $owner2->id, 'locale' => 'nl']);

        $fragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);
        app(AttachFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1);

        $this->assertCount(1, DB::table('context_fragments')->get());
        $this->assertCount(2, DB::table('context_fragment_lookup')->where('fragment_id', $fragment->getFragmentId())->get());

        $this->assertTrue($fragment->fragmentModel()->fresh()->isShared());
    }

    public function test_a_fragment_is_not_considered_shared_when_attached_to_contexts_belonging_to_same_owner()
    {
        $owner = ArticlePage::create();
        $context = ContextModel::create(['owner_type' => $owner->getMorphClass(), 'owner_id' => $owner->id, 'locale' => 'nl']);
        $context2 = ContextModel::create(['owner_type' => $owner->getMorphClass(), 'owner_id' => $owner->id, 'locale' => 'nl']);

        $fragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);
        app(AttachFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1);

        $this->assertCount(1, DB::table('context_fragments')->get());
        $this->assertCount(2, DB::table('context_fragment_lookup')->where('fragment_id', $fragment->getFragmentId())->get());

        $this->assertFalse($fragment->fragmentModel()->fresh()->isShared());
    }
}
