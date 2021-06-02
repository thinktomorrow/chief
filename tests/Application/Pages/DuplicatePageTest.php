<?php

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class DuplicatePageTest extends ChiefTestCase
{
    private $source;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->model(ArticlePage::class, PageManager::class);

        $articlePage = ArticlePage::create([
            'current_state' => PageState::PUBLISHED,
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

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
                        UploadedFile::fake()->image('tt-favicon.png'),
                    ],
                ],
            ],
        ]);

        $this->source = $articlePage->fresh();

        $this->source->setPageState(PageState::PUBLISHED);
        $this->source->created_at = now()->subDay();
        $this->source->updated_at = now()->subDay();
        $this->source->save();
    }

    /** @test */
    public function it_can_duplicate_all_fields()
    {
        $this->disableExceptionHandling();
        $this->asAdmin()->post($this->manager($this->source)->route('duplicate', $this->source));

        $this->assertCount(2, ArticlePage::all());

        $copiedModel = ArticlePage::where('id', '<>', $this->source->id)->first();

        $this->assertEquals($this->source->values, $copiedModel->values);

        // state is set to draft
        $this->assertEquals(PageState::DRAFT, $copiedModel->getPageState());

        // timestamps should be the time of copy
        $this->assertTrue($this->source->created_at->lt($copiedModel->created_at));
        $this->assertTrue($this->source->updated_at->lt($copiedModel->updated_at));
    }

    /** @test */
    public function after_duplicating_we_go_to_the_duplicated_page()
    {
        $response = $this->asAdmin()->post($this->manager($this->source)->route('duplicate', $this->source));

        $copiedModel = ArticlePage::where('id', '<>', $this->source->id)->first();
        $response->assertRedirect($this->manager($copiedModel)->route('edit', $copiedModel));
    }

    /** @test */
    public function context_with_fragments_are_duplicated()
    {
        // Add fragments
        $quote = $this->setupAndCreateQuote($this->source, [], 0);
        $snippet = $this->setupAndCreateSnippet($this->source, 1);

        $response = $this->asAdmin()->post($this->manager($this->source)->route('duplicate', $this->source));

        $copiedModel = ArticlePage::where('id', '<>', $this->source->id)->first();

        $originalFragments = app(FragmentRepository::class)->getByOwner($this->source);
        $fragments = app(FragmentRepository::class)->getByOwner($copiedModel);

        $this->assertCount(2, $fragments);

        // Quote is dynamic so is duplicated as 'shared'
        $this->assertEquals($originalFragments[0]->model_reference, $fragments[0]->model_reference);
        $this->assertEquals($originalFragments[0]->id, $fragments[0]->id);
        $this->assertTrue($originalFragments[0]->fragmentModel()->isShared());
        $this->assertTrue($fragments[0]->fragmentModel()->isShared());

        // Snippet is static so is copied
        $this->assertNotEquals($originalFragments[1]->fragmentModel()->id, $fragments[1]->fragmentModel()->id);

        // method on model to allow to duplicate e.g. duplicate(targetModel);
        // Or dedicated duplicate class Duplicator() => Duplicate::handle()
    }

    /** @test */
    public function page_assets_are_not_duplicated_but_refer_to_same_asset()
    {
        $this->asAdmin()->post($this->manager($this->source)->route('duplicate', $this->source));

        $copiedModel = ArticlePage::where('id', '<>', $this->source->id)->first();

        $this->assertCount(1, $copiedModel->assets());
        $this->assertEquals($this->source->asset('thumb')->id, $copiedModel->asset('thumb')->id);
    }

    /** @test */
    public function it_can_use_a_custom_duplicator()
    {
    }
}
