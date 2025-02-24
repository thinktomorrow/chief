<?php

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\Repositories\FragmentRepository;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class DuplicatePageTest extends ChiefTestCase
{
    private $source;

    public function test_it_can_duplicate_all_fields()
    {
        $this->assertCount(1, ArticlePage::all());

        $this->asAdmin()->post($this->manager($this->source)->route('duplicate', $this->source));

        $this->assertCount(2, ArticlePage::all());

        $copiedModel = ArticlePage::where('id', '<>', $this->source->id)->first();

        // 'custom' is the title attribute in this test
        $this->assertEquals(
            str_replace($this->source->custom, '[Copy] '.$this->source->custom, $this->source->values),
            $copiedModel->values
        );

        // state is set to draft
        $this->assertEquals(PageState::draft, $copiedModel->getState(PageState::KEY));

        // timestamps should be the time of copy
        $this->assertTrue($this->source->created_at->lt($copiedModel->created_at));
        $this->assertTrue($this->source->updated_at->lt($copiedModel->updated_at));
    }

    public function test_after_duplicating_we_go_to_the_duplicated_page()
    {
        $response = $this->asAdmin()->post($this->manager($this->source)->route('duplicate', $this->source));

        $copiedModel = ArticlePage::where('id', '<>', $this->source->id)->first();
        $response->assertRedirect($this->manager($copiedModel)->route('edit', $copiedModel));
    }

    public function test_context_with_fragments_are_duplicated()
    {
        $this->disableExceptionHandling();
        $context = $this->findOrCreateContext($this->source, 'nl');

        $snippet = $this->createAndAttachFragment(SnippetStub::resourceKey(), $context->id, 1);
        $snippet2 = $this->createAndAttachFragment(SnippetStub::resourceKey(), $context->id, 2);

        $this->asAdmin()
            ->post($this->manager($this->source)->route('duplicate', $this->source))
            ->assertRedirect();

        $copiedModel = ArticlePage::whereNotIn('id', [$this->source->id])->first();

        $originalFragments = app(FragmentRepository::class)->getByOwner($this->source, 'nl');
        $fragments = app(FragmentRepository::class)->getByOwner($copiedModel, 'nl');

        $this->assertCount(2, $fragments);

        // First snippet is shared
        $this->assertEquals($originalFragments[0]->fragmentModel()->key, $fragments[0]->fragmentModel()->key);
        $this->assertNotEquals($originalFragments[0]->fragmentModel()->id, $fragments[0]->fragmentModel()->id);
        $this->assertNotEquals($originalFragments[1]->fragmentModel()->id, $fragments[1]->fragmentModel()->id);
    }

    public function test_context_with_shared_fragments_keeps_fragment_shared()
    {
        $context = $this->findOrCreateContext($this->source, 'nl');
        $context2 = $this->findOrCreateContext($otherOwner = ArticlePage::create(), 'nl');

        // Add shared and non-shared fragment
        $snippet = $this->createAndAttachFragment(SnippetStub::resourceKey(), $context->id, 1);
        app(AttachFragment::class)->handle($context2->id, $snippet->getFragmentId(), 0, []);

        $this->asAdmin()
            ->post($this->manager($this->source)->route('duplicate', $this->source))
            ->assertRedirect();

        $copiedModel = ArticlePage::whereNotIn('id', [$this->source->id, $otherOwner->id])->first();

        $originalFragments = app(FragmentRepository::class)->getByOwner($this->source, 'nl');
        $fragments = app(FragmentRepository::class)->getByOwner($copiedModel, 'nl');

        $this->assertCount(1, $fragments);

        // First snippet is shared
        $this->assertEquals($originalFragments[0]->fragmentModel()->key, $fragments[0]->fragmentModel()->key);
        $this->assertEquals($originalFragments[0]->fragmentModel()->id, $fragments[0]->fragmentModel()->id);
        $this->assertTrue($originalFragments[0]->fragmentModel()->isShared());
        $this->assertTrue($fragments[0]->fragmentModel()->isShared());
    }

    public function test_page_assets_are_not_duplicated_but_refer_to_same_asset()
    {
        $this->asAdmin()->post($this->manager($this->source)->route('duplicate', $this->source));

        $copiedModel = ArticlePage::where('id', '<>', $this->source->id)->first();

        $this->assertCount(1, $copiedModel->assets());
        $this->assertEquals($this->source->asset('thumb')->id, $copiedModel->asset('thumb')->id);
    }

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class, PageManager::class);
        chiefRegister()->fragment(SnippetStub::class);

        $articlePage = ArticlePage::create([
            'current_state' => PageState::published,
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        UploadedFile::fake()->image('image.png')->storeAs('test', 'image-temp-name.png');

        $this->asAdmin()->put($this->manager($articlePage)->route('update', $articlePage), [
            'title' => 'new title',
            'custom' => 'custom value',
            'trans' => [
                'nl' => [
                    'content_trans' => 'nl content',
                ],
            ],
            'files' => [
                'thumb' => [
                    'nl' => [
                        'uploads' => [
                            [
                                'id' => 'xxx',
                                'path' => Storage::path('test/image-temp-name.png'),
                                'originalName' => 'image.png',
                                'mimeType' => 'image/png',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $this->source = $articlePage->fresh();

        $this->source->changeState(PageState::KEY, PageState::published);
        $this->source->created_at = now()->subDay();
        $this->source->updated_at = now()->subDay();
        $this->source->save();
    }
}
