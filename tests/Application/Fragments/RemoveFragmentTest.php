<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Events\FragmentRemovedFromContext;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class RemoveFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private SnippetStub $fragment;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->fragment = $this->setupAndCreateSnippet($this->owner);
    }

    /** @test */
    public function a_page_can_remove_a_fragment()
    {
        $this->assertFragmentCount($this->owner, 1);

        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->owner, $this->fragment));

        $this->assertFragmentCount($this->owner, 0);
    }

    /** @test */
    public function a_fragment_can_remove_a_nested_fragment()
    {
        $nestedFragment = $this->createAsFragment(new SnippetStub(), $this->fragment->fragmentModel());

        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->fragment, $nestedFragment))->assertStatus(200);

        $this->assertFragmentCount($this->fragment->fragmentModel(), 0);
    }

    /** @test */
    public function it_can_check_if_a_model_allows_for_removing_a_fragment()
    {
        $this->assertTrue($this->manager($this->owner)->can('fragment-delete'));
        $this->assertTrue($this->manager($this->fragment)->can('fragment-delete'));
    }

    /** @test */
    public function removing_a_fragment_multiple_times_only_removes_it_once()
    {
        $this->assertFragmentCount($this->owner, 1);

        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->owner, $this->fragment));
        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->owner, $this->fragment));

        $this->assertFragmentCount($this->owner, 0);
    }

    /** @test */
    public function removing_a_fragment_emits_event()
    {
        Event::fake();

        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->owner, $this->fragment));
        $this->assertFragmentCount($this->owner, 0);

        Event::assertDispatched(FragmentRemovedFromContext::class);
    }

    /** @test */
    public function removing_a_fragment_soft_deletes_fragment_and_assets()
    {
        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->owner, $this->fragment));
        $this->assertFragmentCount($this->owner, 0);

        $deletedFragmentModel = FragmentModel::withTrashed()->find($this->fragment->fragmentModel()->id);
        $this->assertTrue($deletedFragmentModel->trashed());
        $this->assertNotNull($deletedFragmentModel->deleted_at);
    }

    /** @test */
    public function removing_a_static_fragment_deletes_fragment_and_assets()
    {
        // Add file to static fragment
        $this->asAdmin()->put($this->manager($this->fragment)->route('fragment-update', $this->fragment), [
            'files' => [
                'thumb' => [
                    'nl' => [
                        UploadedFile::fake()->image('tt-favicon.png'),
                    ],
                ],
            ],
        ])->assertStatus(200);

        $this->assertCount(1, $this->fragment->fragmentModel()->fresh()->assetRelation);

        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->owner, $this->fragment));
        $this->assertFragmentCount($this->owner, 0);

        $deletedFragmentModel = FragmentModel::withTrashed()->find($this->fragment->fragmentModel()->id);
        $this->assertTrue($deletedFragmentModel->trashed());
        $this->assertNotNull($deletedFragmentModel->deleted_at);

        $this->assertEquals(0, $deletedFragmentModel->assetRelation()->count());
    }

    /** @test */
    public function removing_a_fragment_doesnt_delete_fragment_when_it_is_used_elsewhere()
    {
        $owner2 = ArticlePage::create();
        $this->addFragment($this->fragment, $owner2);

        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->owner, $this->fragment));
        $this->assertFragmentCount($this->owner, 0);
        $this->assertFragmentCount($owner2, 1);

        $fragmentModel = $this->fragment->fragmentModel()->fresh();
        $this->assertFalse($fragmentModel->trashed());
    }

    /** @test */
    public function softdeleted_fragments_and_assets_are_cleaned_up_by_garbage_collection()
    {
        $this->markTestSkipped();
    }
}
