<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\Chief\Fragments\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class DeleteFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        chiefRegister()->fragment(SnippetStub::class);
    }

    public function test_a_page_can_remove_a_fragment()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);

        FragmentTestHelpers::assertFragmentCount($context->id, 1);

        $this->asAdmin()->delete(route('chief::fragments.delete', [$context->id, $fragment->getFragmentId()]));

        FragmentTestHelpers::assertFragmentCount($context->id, 0);
    }

    public function test_it_will_not_be_detached_from_context_where_fragment_does_not_belong_to()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);
        $otherContext = FragmentTestHelpers::createContext($this->owner);

        FragmentTestHelpers::assertFragmentCount($context->id, 1);
        FragmentTestHelpers::assertFragmentCount($otherContext->id, 0);

        $this->asAdmin()->delete(route('chief::fragments.delete', [$otherContext->id, $fragment->getFragmentId()]));

        FragmentTestHelpers::assertFragmentCount($context->id, 1);
        FragmentTestHelpers::assertFragmentCount($otherContext->id, 0);
    }

    public function test_a_fragment_can_remove_a_nested_fragment()
    {
        $fragment = FragmentTestHelpers::createFragment(SnippetStub::class);
        $context = FragmentTestHelpers::createContext($fragment);
        $fragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);

        FragmentTestHelpers::assertFragmentCount($context->id, 1);

        $this->asAdmin()->delete(route('chief::fragments.nested.delete', [$context->id, $fragment->getFragmentId()]))
            ->assertStatus(200);

        FragmentTestHelpers::assertFragmentCount($context->id, 0);
    }

    public function test_removing_a_fragment_multiple_times_only_removes_it_once()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);

        FragmentTestHelpers::assertFragmentCount($context->id, 1);

        $this->asAdmin()->delete(route('chief::fragments.delete', [$context->id, $fragment->getFragmentId()]));
        $this->asAdmin()->delete(route('chief::fragments.delete', [$context->id, $fragment->getFragmentId()]));

        FragmentTestHelpers::assertFragmentCount($context->id, 0);
    }

    public function test_removing_a_fragment_emits_event()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);

        Event::fake();

        $this->asAdmin()->delete(route('chief::fragments.delete', [$context->id, $fragment->getFragmentId()]));

        Event::assertDispatched(FragmentDetached::class);
    }

    public function test_removing_a_fragment_soft_deletes_fragment_and_assets()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);

        $this->asAdmin()->delete(route('chief::fragments.delete', [$context->id, $fragment->getFragmentId()]));
        FragmentTestHelpers::assertFragmentCount($context->id, 0);

        $deletedFragmentModel = FragmentModel::find($fragment->getFragmentModel()->id);
        $this->assertNull($deletedFragmentModel);
    }

    public function test_removing_a_fragment_deletes_fragment_and_assets()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);

        UploadedFile::fake()->image('image.png')->storeAs('test', 'image-temp-name.png');

        $this->saveFileField($fragment, $fragment->getFragmentModel(), 'thumb', [
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

        $this->assertCount(1, $fragment->getFragmentModel()->fresh()->assetRelation);

        $this->asAdmin()->delete(route('chief::fragments.delete', [$context->id, $fragment->getFragmentId()]));
        FragmentTestHelpers::assertFragmentCount($context->id, 0);

        $deletedFragmentModel = FragmentModel::find($fragment->getFragmentModel()->id);
        $this->assertNull($deletedFragmentModel);

        $this->assertEquals(0, DB::table('assets_pivot')->count());
    }

    public function test_removing_a_fragment_doesnt_delete_fragment_when_it_is_used_elsewhere()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);

        $owner2 = ArticlePage::create();
        $context2 = FragmentTestHelpers::findOrCreateContext($owner2);
        FragmentTestHelpers::attachFragment($context2->id, $fragment->getFragmentId(), null, 1);

        $this->asAdmin()->delete(route('chief::fragments.delete', [$context->id, $fragment->getFragmentId()]));
        FragmentTestHelpers::assertFragmentCount($context->id, 0);
        FragmentTestHelpers::assertFragmentCount($context2->id, 1);

        $this->assertNotNull(FragmentModel::find($fragment->getFragmentModel()->id));
    }
}
