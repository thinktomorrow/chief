<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Fragments\App\Actions\DuplicateFragment;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class DuplicateFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private ContextModel $context;

    private Fragment $fragment;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();

        $this->context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $this->fragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $this->context->id);
    }

    public function test_it_can_duplicate_a_fragment_to_other_context()
    {
        $targetContext = FragmentTestHelpers::createContext($this->owner);

        $this->assertEquals(1, FragmentModel::count());
        $this->assertCount(1, $this->context->fragments()->get());
        $this->assertCount(0, $targetContext->fragments()->get());

        app(DuplicateFragment::class)->handle(
            $this->fragment->getFragmentModel(),
            $this->context->id,
            $targetContext->id,
            null,
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
            $this->fragment->getFragmentModel(),
            $this->context->id,
            $targetContext->id,
            null,
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
        app(AddAsset::class)->handle($this->fragment->getFragmentModel(), $asset, 'xxx', 'nl', 2, ['foo' => 'bar']);

        $targetContext = FragmentTestHelpers::findOrCreateContext($this->owner);

        app(DuplicateFragment::class)->handle(
            $this->fragment->getFragmentModel(),
            $this->context->id,
            $targetContext->id,
            null,
            1
        );

        $originalFragmentAsset = $this->context->fragments->first()->assetRelation()->first();
        $duplicatedFragmentAsset = $targetContext->fragments->first()->assetRelation()->first();

        $this->assertEquals($originalFragmentAsset->id, $duplicatedFragmentAsset->id);
        $this->assertEquals($originalFragmentAsset->pivot->data, $duplicatedFragmentAsset->pivot->data);
        $this->assertEquals($originalFragmentAsset->pivot->type, $duplicatedFragmentAsset->pivot->type);
        $this->assertEquals($originalFragmentAsset->pivot->locale, $duplicatedFragmentAsset->pivot->locale);
    }

    public function test_nested_fragments_are_also_duplicated()
    {
        $targetContext = FragmentTestHelpers::createContext($this->owner);

        // Create nested fragment
        $nestedFragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $this->context->id, $this->fragment->getFragmentId());

        app(DuplicateFragment::class)->handle(
            $this->fragment->getFragmentModel(),
            $this->context->id,
            $targetContext->id,
            null,
            1
        );

        $this->assertEquals(4, FragmentModel::count());
        $this->assertCount(2, $this->context->fragments()->get());
        $this->assertCount(2, $targetContext->fragments()->get());

        $this->assertNotEquals($this->fragment->getFragmentId(), $targetContext->fragments()->first()->id);
    }
}
