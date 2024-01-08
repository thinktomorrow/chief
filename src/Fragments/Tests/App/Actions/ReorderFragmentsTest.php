<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class ReorderFragmentsTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_it_can_reorder_fragments()
    {

    }

    public function test_it_emits_event_after_reordering()
    {

    }

    public function test_it_can_store_a_new_fragment_with_a_specific_order()
    {
        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);
        $snippet1 = FragmentTestAssist::createContextAndAttachFragment($this->owner, SnippetStub::class, 'nl', 1);
        $snippet2 = FragmentTestAssist::createContextAndAttachFragment($this->owner, SnippetStub::class, 'nl', 2);
        $snippet3 = FragmentTestAssist::createContextAndAttachFragment($this->owner, SnippetStub::class, 'nl', 3);

        $response = $this->asAdmin()->post(route('chief::fragments.store', [$context->id, SnippetStub::resourceKey()]), [
            'title' => 'new-title',
            'order' => 1,
        ])->assertStatus(201);

        $insertedFragmentId = $response->getOriginalContent()['data']['fragmentmodel_id'];

        $fragments = app(FragmentRepository::class)->getByOwner($this->owner, 'nl');
        $this->assertCount(4, $fragments);

        $this->assertEquals($snippet1->modelReference(), $fragments[0]->modelReference());
        $this->assertEquals($insertedFragmentId, $fragments[1]->modelReference()->id());
        $this->assertEquals($snippet2->modelReference(), $fragments[2]->modelReference());
        $this->assertEquals($snippet3->modelReference(), $fragments[3]->modelReference());

        // Assert order is updated accordingly
        $this->assertEquals(0, $fragments[0]->fragmentModel()->pivot->order);
        $this->assertEquals(1, $fragments[1]->fragmentModel()->pivot->order);
        $this->assertEquals(2, $fragments[2]->fragmentModel()->pivot->order);
        $this->assertEquals(3, $fragments[3]->fragmentModel()->pivot->order);
    }
}
