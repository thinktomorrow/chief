<?php

namespace Thinktomorrow\Chief\Assets\Tests\App;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Forms\Tests\TestSupport\CustomAsset;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

class IsolatingFileTest extends ChiefTestCase
{
    use TestingFileUploads;

    private $model;

    private Resource $resource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setUpAndCreateArticle();
        $this->resource = app(ArticlePageResource::class);
    }

    public function test_it_can_isolate_asset()
    {
        $this->uploadImageField($this->model, 'thumb');

        // Attach same asset to other model
        $this->attachFile(
            $model2 = ArticlePage::create(),
            'thumb',
            Asset::first()->id
        );

        $this->assertDatabaseCount('assets', 1);
        $this->assertDatabaseCount('assets_pivot', 2);

        app(FileApplication::class)->isolateAsset(
            $this->model->modelReference()->get(),
            'thumb',
            'nl',
            Asset::first()->id,
        );

        $this->model->refresh();
        $this->assertDatabaseCount('assets', 2);
        $this->assertDatabaseCount('assets_pivot', 2);
        $this->assertTrue(DB::table('assets_pivot')->where('asset_id', $model2->asset('thumb')->id)->where('entity_id', $model2->id)->exists());
        $this->assertTrue(DB::table('assets_pivot')->where('asset_id', $this->model->asset('thumb')->id)->where('entity_id', $this->model->id)->exists());
    }

    public function test_it_can_take_order_and_pivot_data(): void
    {
        $this->uploadImageField($this->model, 'thumb', 'test/image.png', [
            'fieldValues' => [
                'alt' => 'test caption',
            ],
        ]);

        app(FileApplication::class)->isolateAsset(
            $this->model->modelReference()->get(),
            'thumb',
            'nl',
            Asset::first()->id,
        );

        $this->model->refresh();

        $this->assertEquals('test caption', $this->model->asset('thumb')->getData('alt'));
        $this->assertEquals(0, $this->model->asset('thumb')->pivot->order);
    }

    public function test_it_can_detach_and_isolate_asset_with_custom_asset_type()
    {
        config()->set('thinktomorrow.assetlibrary.types', [
            'custom' => CustomAsset::class,
            'default' => Asset::class,
        ]);

        $this->uploadImageField($this->model, ArticlePage::FILEFIELD_ASSETTYPE_KEY);

        app(FileApplication::class)->isolateAsset(
            $this->model->modelReference()->get(),
            ArticlePage::FILEFIELD_ASSETTYPE_KEY,
            'nl',
            $this->model->asset(ArticlePage::FILEFIELD_ASSETTYPE_KEY, 'nl')->id,
        );

        $asset = $this->model->fresh()->asset(ArticlePage::FILEFIELD_ASSETTYPE_KEY);

        $this->assertEquals('custom', $asset->asset_type);
        $this->assertInstanceOf(CustomAsset::class, $asset);
    }
}
