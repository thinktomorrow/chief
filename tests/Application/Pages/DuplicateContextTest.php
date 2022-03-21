<?php

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\ManagedModels\Actions\Duplicate\DuplicateContext;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class DuplicateContextTest extends ChiefTestCase
{
    private $source;
    private $target;
    private $staticFragment;
    private $fragment;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        Quote::migrateUp();

        $this->disableExceptionHandling();

        app(Register::class)->resource(ArticlePageResource::class, PageManager::class);
        app(Register::class)->fragment(Quote::class);
        app(Register::class)->fragment(SnippetStub::class);

        $fragmentManager = $this->manager(Quote::class);
        $staticFragmentManager = $this->manager(SnippetStub::class);

        $this->source = ArticlePage::create();
        $this->target = ArticlePage::create();
        $this->staticFragment = new SnippetStub();
        $this->fragment = Quote::create();

        // Add fragments
        $this->asAdmin()->post($staticFragmentManager->route('fragment-store', $this->source));
        $this->asAdmin()->post($fragmentManager->route('fragment-store', $this->source), [
            'custom' => 'custom value',
        ]);
    }

    /** @test */
    public function fragments_can_be_duplicated()
    {
        $this->assertEquals(1, ContextModel::count());
        $this->assertEquals(2, FragmentModel::count());

        app(DuplicateContext::class)->handle($this->source, $this->target);

        $this->assertEquals(2, ContextModel::count());
        $this->assertEquals(4, FragmentModel::count());

        $originalContext = ContextModel::ownedBy($this->source);
        $copiedContext = ContextModel::ownedBy($this->target);

        // Copy of static fragment
        $originalStaticFragment = $originalContext->fragments->first();
        $copiedStaticFragment = $copiedContext->fragments->first();
        $this->assertNotEquals($originalStaticFragment->id, $copiedStaticFragment->id);
        $this->assertEquals($originalStaticFragment->order, $copiedStaticFragment->order);
        $this->assertEquals($originalStaticFragment->model_reference, $copiedStaticFragment->model_reference);
        $this->assertEquals($originalStaticFragment->values, $copiedStaticFragment->values);

        // Copy of fragment
        $originalFragment = $originalContext->fragments->last();
        $copiedFragment = $copiedContext->fragments->last();
        $this->assertNotEquals($originalFragment->id, $copiedFragment->id);
        $this->assertEquals($originalFragment->order, $copiedFragment->order);
        $this->assertEquals($originalFragment->model_reference, $copiedFragment->model_reference);
    }

    /** @test */
    public function fragments_assets_can_be_duplicated()
    {
        $this->markTestSkipped();

        $asset = AssetUploader::upload(UploadedFile::fake()->image('image.png'));
        $this->staticFragment->fragmentModel()->assetRelation()->attach($asset);

        app(DuplicateContext::class)->handle($this->source, $this->target);

        $this->assertEquals(2, ContextModel::count());
        $this->assertEquals(4, FragmentModel::count());

        $originalContext = ContextModel::ownedBy($this->source);
        $copiedContext = ContextModel::ownedBy($this->target);

        $originalStaticFragment = $originalContext->fragments->first();
        $copiedStaticFragment = $copiedContext->fragments->first();
    }

    /** @test */
    public function the_pagebuilder_setup_of_an_existing_page_can_be_duplicated()
    {
        $this->markTestIncomplete();

        $this->asAdmin()->post(route('chief.back.managers.store', 'singles'), array_merge([
            'template' => get_class($this->page).'@'.$this->page->id,
        ], $this->validPageParams()));

        $page = Single::find(3);

        $this->assertCount(3, $page->children());
    }
}
