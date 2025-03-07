<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments\Crud;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Events\FragmentDetached;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class DeleteFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private SnippetStub $fragment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->fragment = $this->setupAndCreateSnippet($this->owner);
    }

    public function test_a_page_can_remove_a_fragment()
    {
        $this->assertFragmentCount($this->owner, 1);

        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->owner, $this->fragment));

        $this->assertFragmentCount($this->owner, 0);
    }

    public function test_a_fragment_can_remove_a_nested_fragment()
    {
        $nestedFragment = $this->createAsFragment(new SnippetStub, $this->fragment->fragmentModel());

        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->fragment, $nestedFragment))->assertStatus(200);

        $this->assertFragmentCount($this->fragment->fragmentModel(), 0);
    }

    public function test_it_can_check_if_a_model_allows_for_removing_a_fragment()
    {
        $this->assertTrue($this->manager($this->fragment)->can('fragment-delete'));
    }

    public function test_removing_a_fragment_multiple_times_only_removes_it_once()
    {
        $this->assertFragmentCount($this->owner, 1);

        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->owner, $this->fragment));
        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->owner, $this->fragment));

        $this->assertFragmentCount($this->owner, 0);
    }

    public function test_removing_a_fragment_emits_event()
    {
        Event::fake();

        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->owner, $this->fragment));
        $this->assertFragmentCount($this->owner, 0);

        Event::assertDispatched(FragmentDetached::class);
    }

    public function test_removing_a_fragment_soft_deletes_fragment_and_assets()
    {
        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->owner, $this->fragment));
        $this->assertFragmentCount($this->owner, 0);

        $deletedFragmentModel = FragmentModel::withTrashed()->find($this->fragment->fragmentModel()->id);
        $this->assertTrue($deletedFragmentModel->trashed());
        $this->assertNotNull($deletedFragmentModel->deleted_at);
    }

    public function test_removing_a_static_fragment_deletes_fragment_and_assets()
    {
        UploadedFile::fake()->image('image.png')->storeAs('test', 'image-temp-name.png');

        $this->saveFileField($this->fragment, $this->fragment->fragmentModel(), 'thumb', [
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

        $this->assertCount(1, $this->fragment->fragmentModel()->fresh()->assetRelation);

        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->owner, $this->fragment));
        $this->assertFragmentCount($this->owner, 0);

        $deletedFragmentModel = FragmentModel::withTrashed()->find($this->fragment->fragmentModel()->id);
        $this->assertTrue($deletedFragmentModel->trashed());
        $this->assertNotNull($deletedFragmentModel->deleted_at);

        $this->assertEquals(0, $deletedFragmentModel->assetRelation()->count());
    }

    public function test_removing_a_fragment_doesnt_delete_fragment_when_it_is_used_elsewhere()
    {
        $owner2 = ArticlePage::create();
        $this->addFragment($this->fragment, $owner2);

        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->owner, $this->fragment));
        $this->assertFragmentCount($this->owner, 0);
        $this->assertFragmentCount($owner2, 1);

        $fragmentModel = $this->fragment->fragmentModel()->fresh();
        $this->assertFalse($fragmentModel->trashed());
    }

    public function test_softdeleted_fragments_and_assets_are_cleaned_up_by_garbage_collection()
    {
        $this->markTestSkipped();
    }
}
