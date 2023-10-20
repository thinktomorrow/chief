<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\Resource\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentModel;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class DeleteFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        chiefRegister()->fragment(SnippetStub::class);
    }

    public function test_a_page_can_remove_a_fragment()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'fr');
        $fragment = $this->createAndAttachFragment(SnippetStub::resourceKey(), $context->id);

        $this->assertFragmentCount($this->owner, 'fr', 1);

        $this->asAdmin()->delete(route('chief::fragments.delete', [$context->id, $fragment->getFragmentId()]));

        $this->assertFragmentCount($this->owner, 'fr', 0);
    }

    public function test_it_will_not_be_detached_from_context_where_fragment_does_not_belong_to()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'fr');
        $fragment = $this->createAndAttachFragment(SnippetStub::resourceKey(), $context->id);
        $otherContext = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'en');

        $this->assertFragmentCount($this->owner, 'fr', 1);
        $this->assertFragmentCount($this->owner, 'en', 0);

        $this->asAdmin()->delete(route('chief::fragments.delete', [$otherContext->id, $fragment->getFragmentId()]));

        $this->assertFragmentCount($this->owner, 'fr', 1);
        $this->assertFragmentCount($this->owner, 'en', 0);
    }

    public function test_a_fragment_can_remove_a_nested_fragment()
    {
        $fragmentId = app(CreateFragment::class)->handle(SnippetStub::resourceKey(), ['title' => 'owning fragment'], []);
        $context = ContextModel::create(['owner_type' => FragmentModel::resourceKey(), 'owner_id' => $fragmentId, 'locale' => 'nl']);
        $fragment = $this->createAndAttachFragment(SnippetStub::resourceKey(), $context->id);

        $this->asAdmin()->delete(route('chief::fragments.nested.delete', [$context->id, $fragment->getFragmentId()]))
            ->assertStatus(200);

        $this->assertFragmentCount(FragmentModel::find($fragmentId), 'nl', 0);
    }

    public function test_removing_a_fragment_multiple_times_only_removes_it_once()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'fr');
        $fragment = $this->createAndAttachFragment(SnippetStub::resourceKey(), $context->id);

        $this->assertFragmentCount($this->owner, 'fr', 1);

        $this->asAdmin()->delete(route('chief::fragments.delete', [$context->id, $fragment->getFragmentId()]));
        $this->asAdmin()->delete(route('chief::fragments.delete', [$context->id, $fragment->getFragmentId()]));

        $this->assertFragmentCount($this->owner, 'fr', 0);
    }

    public function test_removing_a_fragment_emits_event()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $fragment = $this->createAndAttachFragment(SnippetStub::resourceKey(), $context->id);

        Event::fake();

        $this->asAdmin()->delete(route('chief::fragments.delete', [$context->id, $fragment->getFragmentId()]));
        $this->assertFragmentCount($this->owner, 'nl', 0);

        Event::assertDispatched(FragmentDetached::class);
    }

    public function test_removing_a_fragment_soft_deletes_fragment_and_assets()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $fragment = $this->createAndAttachFragment(SnippetStub::resourceKey(), $context->id);

        $this->asAdmin()->delete(route('chief::fragments.delete', [$context->id, $fragment->getFragmentId()]));
        $this->assertFragmentCount($this->owner, 'nl', 0);

        $deletedFragmentModel = FragmentModel::withTrashed()->find($fragment->fragmentModel()->id);
        $this->assertTrue($deletedFragmentModel->trashed());
        $this->assertNotNull($deletedFragmentModel->deleted_at);
    }

    public function test_removing_a_fragment_deletes_fragment_and_assets()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $fragment = $this->createAndAttachFragment(SnippetStub::resourceKey(), $context->id);

        UploadedFile::fake()->image('image.png')->storeAs('test', 'image-temp-name.png');

        $this->saveFileField($fragment, $fragment->fragmentModel(), 'thumb', [
            'nl' => [
                'uploads' => [
                    [
                        'id' => 'xxx',
                        'path' => Storage::path('test/image-temp-name.png'),
                        'originalName' => 'image.png',
                        'mimeType' => 'image/png',
                        'fieldValues' => [],
                    ],
                ],
            ],
        ]);

        $this->assertCount(1, $fragment->fragmentModel()->fresh()->assetRelation);

        $this->asAdmin()->delete(route('chief::fragments.delete', [$context->id, $fragment->getFragmentId()]));
        $this->assertFragmentCount($this->owner, 'nl', 0);

        $deletedFragmentModel = FragmentModel::withTrashed()->find($fragment->fragmentModel()->id);
        $this->assertTrue($deletedFragmentModel->trashed());
        $this->assertNotNull($deletedFragmentModel->deleted_at);

        $this->assertEquals(0, $deletedFragmentModel->assetRelation()->count());
    }

    public function test_removing_a_fragment_doesnt_delete_fragment_when_it_is_used_elsewhere()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'fr');
        $fragment = $this->createAndAttachFragment(SnippetStub::resourceKey(), $context->id);

        $owner2 = ArticlePage::create();
        $context2 = ContextModel::create(['owner_type' => $owner2->getMorphClass(), 'owner_id' => $owner2->id, 'locale' => 'fr']);

        app(AttachFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1);

        $this->asAdmin()->delete(route('chief::fragments.delete', [$context->id, $fragment->getFragmentId()]));
        $this->assertFragmentCount($this->owner, 'fr', 0);
        $this->assertFragmentCount($owner2, 'fr', 1);

        $fragmentModel = $fragment->fragmentModel()->fresh();
        $this->assertFalse($fragmentModel->trashed());
    }
}
