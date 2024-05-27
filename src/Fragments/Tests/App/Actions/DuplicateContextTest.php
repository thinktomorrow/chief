<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Fragments\Actions\DuplicateContext;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class DuplicateContextTest extends ChiefTestCase
{
    private $context;
    private $fragment;

    private ArticlePage $owner;
    private ArticlePage $owner2;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
        $this->owner2 = ArticlePage::create();

        $this->context = FragmentTestAssist::findOrCreateContext($this->owner, );
        $this->fragment = FragmentTestAssist::createAndAttachFragment(SnippetStub::class, $this->context->id);
    }

    public function test_context_can_be_duplicated()
    {
        $this->assertEquals(1, ContextModel::count());
        $this->assertEquals(1, FragmentModel::count());

        app(DuplicateContext::class)->handle($this->context->id, $this->owner2, 'en');

        $this->assertEquals(2, ContextModel::count());
        $this->assertEquals(2, FragmentModel::count());
    }

    public function test_fragments_will_be_duplicated()
    {
        app(DuplicateContext::class)->handle($this->context->id, $this->owner2, 'en');

        $originalContext = app(ContextRepository::class)->getByOwner($this->owner)->first();
        $duplicatedContext = app(ContextRepository::class)->getByOwner($this->owner2)->first();

        // Check duplicated fragment
        $originalStaticFragment = $originalContext->fragments->first();
        $duplicatedFragment = $duplicatedContext->fragments->first();
        $this->assertNotEquals($originalStaticFragment->id, $duplicatedFragment->id);
        $this->assertEquals($originalStaticFragment->order, $duplicatedFragment->order);
        $this->assertEquals($originalStaticFragment->key, $duplicatedFragment->key);
        $this->assertEquals($originalStaticFragment->values, $duplicatedFragment->values);
    }

    public function test_fragments_assets_can_be_duplicated()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        app(AddAsset::class)->handle($this->fragment->fragmentModel(), $asset, 'xxx', 'nl', 2, ['foo' => 'bar']);

        app(DuplicateContext::class)->handle($this->context->id, $this->owner2, 'nl');

        $this->assertEquals(2, ContextModel::count());
        $this->assertEquals(2, FragmentModel::count());
        $this->assertEquals(1, Asset::count());

        $originalContext = app(ContextRepository::class)->getByOwner($this->owner)->first();
        $duplicatedContext = app(ContextRepository::class)->getByOwner($this->owner2)->first();

        $originalFragmentAsset = $originalContext->fragments->first()->assetRelation()->first();
        $duplicatedFragmentAsset = $duplicatedContext->fragments->first()->assetRelation()->first();

        $this->assertEquals($originalFragmentAsset->id, $duplicatedFragmentAsset->id);
        $this->assertEquals($originalFragmentAsset->pivot->data, $duplicatedFragmentAsset->pivot->data);
        $this->assertEquals($originalFragmentAsset->pivot->type, $duplicatedFragmentAsset->pivot->type);
        $this->assertEquals($originalFragmentAsset->pivot->locale, $duplicatedFragmentAsset->pivot->locale);
    }

    public function test_it_can_duplicate_context_with_nested_fragments()
    {
        // Create nested fragment
        $nestedContext = FragmentTestAssist::createContext($this->fragment);
        $nestedFragment = FragmentTestAssist::createAndAttachFragment(SnippetStub::class, $nestedContext->id);

        $this->assertEquals(2, ContextModel::count());
        $this->assertEquals(2, FragmentModel::count());

        app(DuplicateContext::class)->handle($this->context->id, $this->owner2, 'en');

        $this->assertEquals(4, ContextModel::count());
        $this->assertEquals(4, FragmentModel::count());

        $originalContext = app(ContextRepository::class)->getByOwner($this->owner)->first();
        $duplicatedContext = app(ContextRepository::class)->getByOwner($this->owner2)->first();

        // Check duplicated fragment
        $originalNestedContext = app(ContextRepository::class)->findNestedContextByOwner($originalContext->fragments->first());
        $duplicatedNestedContext = app(ContextRepository::class)->findNestedContextByOwner($duplicatedContext->fragments->first());
        $originalNestedFragment = $originalNestedContext->fragments()->first();
        $duplicatedNestedFragment = $duplicatedNestedContext->fragments()->first();

        $this->assertNotEquals($originalNestedFragment->id, $duplicatedNestedFragment->id);
        $this->assertEquals($originalNestedFragment->order, $duplicatedNestedFragment->order);
        $this->assertEquals($originalNestedFragment->key, $duplicatedNestedFragment->key);
        $this->assertEquals($originalNestedFragment->values, $duplicatedNestedFragment->values);
    }
}
