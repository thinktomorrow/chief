<?php

namespace Tests\App\Actions;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Fragments\App\Actions\DuplicateFragment;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class DuplicateFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private ContextModel $context;
    private Fragmentable $fragment;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();

        $this->context = FragmentTestAssist::findOrCreateContext($this->owner);
        $this->fragment = FragmentTestAssist::createAndAttachFragment(SnippetStub::class, $this->context->id);
    }

    public function test_it_can_duplicate_a_fragment_to_other_context()
    {
        $targetContext = FragmentTestAssist::createContext($this->owner);

        $this->assertEquals(1, FragmentModel::count());
        $this->assertCount(1, $this->context->fragments()->get());
        $this->assertCount(0, $targetContext->fragments()->get());

        app(DuplicateFragment::class)->handle(
            $this->context,
            $targetContext,
            $this->fragment->fragmentModel(),
            1
        );

        $this->assertEquals(2, FragmentModel::count());
        $this->assertCount(1, $this->context->fragments()->get());
        $this->assertCount(1, $targetContext->fragments()->get());
    }

    public function test_it_can_duplicate_fragment_to_same_context()
    {
        $targetContext = $this->context;

        $this->assertEquals(1, FragmentModel::count());
        $this->assertCount(1, $this->context->fragments()->get());

        app(DuplicateFragment::class)->handle(
            $this->context,
            $targetContext,
            $this->fragment->fragmentModel(),
            1
        );

        $this->assertEquals(2, FragmentModel::count());
        $this->assertCount(2, $this->context->fragments()->get());
    }

    public function test_it_can_duplicate_a_fragment_with_assets()
    {
        // Create asset and attach to fragment
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();
        app(AddAsset::class)->handle($this->fragment->fragmentModel(), $asset, 'xxx', 'nl', 2, ['foo' => 'bar']);

        $targetContext = FragmentTestAssist::findOrCreateContext($this->owner);

        app(DuplicateFragment::class)->handle(
            $this->context,
            $targetContext,
            $this->fragment->fragmentModel(),
            1
        );

        $originalFragmentAsset = $this->context->fragments->first()->assetRelation()->first();
        $duplicatedFragmentAsset = $targetContext->fragments->first()->assetRelation()->first();

        $this->assertEquals($originalFragmentAsset->id, $duplicatedFragmentAsset->id);
        $this->assertEquals($originalFragmentAsset->pivot->data, $duplicatedFragmentAsset->pivot->data);
        $this->assertEquals($originalFragmentAsset->pivot->type, $duplicatedFragmentAsset->pivot->type);
        $this->assertEquals($originalFragmentAsset->pivot->locale, $duplicatedFragmentAsset->pivot->locale);
    }

    public function test_it_can_duplicate_fragment_including_child_fragments()
    {
        // TODO: wip
        $targetContext = FragmentTestAssist::createContext($this->owner);

        // Create nested fragment
        $nestedContext = FragmentTestAssist::createContext($this->fragment);
        $nestedFragment = FragmentTestAssist::createAndAttachFragment(SnippetStub::class, $nestedContext->id);

        $this->assertEquals(3, ContextModel::count());
        $this->assertEquals(2, FragmentModel::count());

        app(DuplicateFragment::class)->handle($this->context, $targetContext, $this->fragment->fragmentModel(), 1);

        $this->assertEquals(4, ContextModel::count());
        $this->assertEquals(4, FragmentModel::count());

        // Check duplicated nested fragment
        $originalNestedContext = app(ContextRepository::class)->findNestedContextByOwner($this->context->fragments()->first());
        $duplicatedNestedContext = app(ContextRepository::class)->findNestedContextByOwner($targetContext->fragments()->first());
        $originalNestedFragment = $originalNestedContext->fragments()->first();
        $duplicatedNestedFragment = $duplicatedNestedContext->fragments()->first();

        $this->assertNotEquals($originalNestedFragment->id, $duplicatedNestedFragment->id);
        $this->assertEquals($originalNestedFragment->order, $duplicatedNestedFragment->order);
        $this->assertEquals($originalNestedFragment->key, $duplicatedNestedFragment->key);
        $this->assertEquals($originalNestedFragment->values, $duplicatedNestedFragment->values);
    }

    public function test_it_can_duplicate_shared_fragment()
    {

    }
}
