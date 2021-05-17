<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Events\FragmentRemovedFromContext;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class RemoveFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private Quote $fragment;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
    }

    /** @test */
    public function a_page_can_remove_a_fragment()
    {
        $fragment = $this->setupAndCreateQuote($this->owner);
        $this->assertFragmentCount($this->owner, 1);

        $this->asAdmin()->delete($this->manager($fragment)->route('fragment-delete', $this->owner, $fragment));

        $this->assertFragmentCount($this->owner, 0);
    }

    /** @test */
    public function a_fragment_can_remove_a_nested_fragment()
    {
        $fragment = $this->setupAndCreateQuote($this->owner);
        $nestedFragment = $this->createAsFragment(ArticlePage::create(), $fragment->fragmentModel());

        $this->asAdmin()->delete($this->manager($fragment)->route('fragment-delete', $fragment, $nestedFragment))->assertStatus(200);

        $this->assertFragmentCount($fragment->fragmentModel(), 0);
    }

    /** @test */
    public function it_can_check_if_a_model_allows_for_removing_a_fragment()
    {
        $fragment = $this->setupAndCreateQuote($this->owner);

        $this->assertTrue($this->manager($this->owner)->can('fragment-delete'));
        $this->assertTrue($this->manager($fragment)->can('fragment-delete'));
    }

    /** @test */
    public function removing_a_fragment_multiple_times_only_removes_it_once()
    {
        $fragment = $this->setupAndCreateQuote($this->owner);
        $this->assertFragmentCount($this->owner, 1);

        $this->asAdmin()->delete($this->manager($fragment)->route('fragment-delete', $this->owner, $fragment));
        $this->asAdmin()->delete($this->manager($fragment)->route('fragment-delete', $this->owner, $fragment));

        $this->assertFragmentCount($this->owner, 0);
    }

    /** @test */
    public function removing_a_fragment_emits_event()
    {
        Event::fake();
        $fragment = $this->setupAndCreateQuote($this->owner);

        $this->asAdmin()->delete($this->manager($fragment)->route('fragment-delete', $this->owner, $fragment));
        $this->assertFragmentCount($this->owner, 0);

        Event::assertDispatched(FragmentRemovedFromContext::class);
    }

    /** @test */
    public function removing_a_fragment_soft_deletes_fragment_and_assets()
    {
        $fragment = $this->setupAndCreateQuote($this->owner);

        $this->asAdmin()->delete($this->manager($fragment)->route('fragment-delete', $this->owner, $fragment));
        $this->assertFragmentCount($this->owner, 0);

        $deletedFragmentModel = FragmentModel::withTrashed()->find($fragment->fragmentModel()->id);
        $this->assertTrue($deletedFragmentModel->trashed());
        $this->assertNotNull($deletedFragmentModel->deleted_at);
    }

    /** @test */
    public function removing_a_static_fragment_soft_deletes_fragment_and_assets()
    {
        $fragment = $this->setupAndCreateSnippet($this->owner);

        // Add file to static fragment
        $this->asAdmin()->put($this->manager($fragment)->route('fragment-update', $fragment), [
            'files' => [
                'thumb' => [
                    'nl' => [
                        UploadedFile::fake()->image('tt-favicon.png'),
                    ],
                ],
            ],
        ])->assertStatus(200);

        $this->assertCount(1, $fragment->fragmentModel()->fresh()->assetRelation);

        $this->asAdmin()->delete($this->manager($fragment)->route('fragment-delete', $this->owner, $fragment));
        $this->assertFragmentCount($this->owner, 0);

        $deletedFragmentModel = FragmentModel::withTrashed()->find($fragment->fragmentModel()->id);
        $this->assertTrue($deletedFragmentModel->trashed());
        $this->assertNotNull($deletedFragmentModel->deleted_at);

        // TODO: unused does nothing - better is to set softdeletion on media model.
        $asset = $deletedFragmentModel->assetRelation()->withPivot('unused')->first();
        $this->assertTrue($asset->isUnused());
    }

    /** @test */
    public function removing_a_fragment_doesnt_delete_fragment_when_it_is_used_elsewhere()
    {
        $this->disableExceptionHandling();
        $fragment = $this->setupAndCreateSnippet($this->owner);

        $owner2 = ArticlePage::create();
        $this->addFragment($fragment, $owner2);

        $this->asAdmin()->delete($this->manager($fragment)->route('fragment-delete', $this->owner, $fragment));
        $this->assertFragmentCount($this->owner, 0);
        $this->assertFragmentCount($owner2, 1);

        $fragmentModel = $fragment->fragmentModel()->fresh();
        $this->assertFalse($fragmentModel->trashed());
    }

    /** @test */
    public function softdeleted_fragments_and_assets_are_cleaned_up_by_garbage_collection()
    {
        $this->markTestSkipped();
    }
}
