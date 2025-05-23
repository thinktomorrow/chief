<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\ContextActions;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\DuplicateContext;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class DuplicateContextTest extends ChiefTestCase
{
    private $context;

    private $fragment;

    private ArticlePage $owner;

    private ArticlePage $owner2;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
        $this->owner2 = ArticlePage::create();

        $this->context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $this->fragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $this->context->id);
    }

    public function test_context_can_be_duplicated()
    {
        $this->assertEquals(1, ContextModel::count());
        $this->assertEquals(1, FragmentModel::count());

        app(ContextApplication::class)->duplicate(new DuplicateContext($this->context->id, $this->owner2));

        $this->assertEquals(2, ContextModel::count());
        $this->assertEquals(2, FragmentModel::count());
    }

    public function test_fragments_will_be_duplicated()
    {
        app(ContextApplication::class)->duplicate(new DuplicateContext($this->context->id, $this->owner2));

        $originalContext = app(ContextRepository::class)->getByOwner($this->owner->modelReference())->first();
        $duplicatedContext = app(ContextRepository::class)->getByOwner($this->owner2->modelReference())->first();

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

        app(AddAsset::class)->handle($this->fragment->getFragmentModel(), $asset, 'xxx', 'nl', 2, ['foo' => 'bar']);

        app(ContextApplication::class)->duplicate(new DuplicateContext($this->context->id, $this->owner2));

        $this->assertEquals(2, ContextModel::count());
        $this->assertEquals(2, FragmentModel::count());
        $this->assertEquals(1, Asset::count());

        $originalContext = app(ContextRepository::class)->getByOwner($this->owner->modelReference())->first();
        $duplicatedContext = app(ContextRepository::class)->getByOwner($this->owner2->modelReference())->first();

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
        $nestedFragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $this->context->id, $this->fragment->getFragmentId());

        $this->assertEquals(1, ContextModel::count());
        $this->assertEquals(2, FragmentModel::count());

        app(ContextApplication::class)->duplicate(new DuplicateContext($this->context->id, $this->owner2));

        $this->assertEquals(2, ContextModel::count());
        $this->assertEquals(4, FragmentModel::count());

        $originalContext = app(ContextRepository::class)->getByOwner($this->owner->modelReference())->first();
        $duplicatedContext = app(ContextRepository::class)->getByOwner($this->owner2->modelReference())->first();

        // Check duplicated fragment
        $originalFragmentCollection = app(FragmentRepository::class)->getFragmentCollection($originalContext->id);
        $duplicatedFragmentCollection = app(FragmentRepository::class)->getFragmentCollection($duplicatedContext->id);

        foreach ($originalFragmentCollection->flatten() as $originalFragment) {
            $duplicatedFragment = $duplicatedFragmentCollection->flatten()->find('key', $originalFragment->key);

            $this->assertNotEquals($originalFragment->id, $duplicatedFragment->id);
            $this->assertEquals($originalFragment->key, $duplicatedFragment->key);
            $this->assertEquals($originalFragment->pivot_order, $duplicatedFragment->pivot_order);
            $this->assertEquals($originalFragment->values, $duplicatedFragment->values);
        }
    }
}
