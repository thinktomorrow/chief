<?php

namespace Thinktomorrow\Chief\Assets\Tests\App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Assets\App\FileHelper;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Forms\Tests\stubs\CustomAsset;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

class IsolateFileTest extends ChiefTestCase
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

    protected function tearDown(): void
    {
        //        Storage::delete('test/image-temp-name.png');
        //        Storage::delete('test/image-second-temp-name.jpg');

        parent::tearDown();
    }

    public function test_it_can_detach_and_isolate_asset()
    {
        $filename = 'image.png';
        $mimeType = 'image/png';

        $tempName = Str::random().'.'.FileHelper::getExtension($filename);
        UploadedFile::fake()->image($filename)->storeAs('test', $tempName);

        $this->uploadAsset(
            'test/'.$tempName,
            $filename,
            $mimeType,
            $this->model,
            $this->resource,
        );

        $model2 = $this->setUpAndCreateArticle([], false);
        $this->uploadAsset(
            'test/'.$tempName,
            $filename,
            $mimeType,
            $model2,
            $this->resource,
        );

        $this->assertDatabaseCount('assets_pivot', 2);

        app(FileApplication::class)->isolateAsset(
            $this->model->modelReference()->get(),
            'thumb',
            'nl',
            $this->model->asset('thumb', 'nl')->id,
        );

        $this->model->refresh();
        $this->assertDatabaseCount('assets_pivot', 2);
        $this->assertTrue(DB::table('assets_pivot')->where('asset_id', $model2->asset('thumb')->id)->where('entity_id', $model2->id)->exists());
        $this->assertTrue(DB::table('assets_pivot')->where('asset_id', $this->model->asset('thumb')->id)->where('entity_id', $this->model->id)->exists());
    }

    public function test_it_can_detach_and_isolate_asset_from_other_disk()
    {
        UploadedFile::fake()->image('image-nl.png', '10', '80')->storeAs('test', 'image-temp-name-nl.png');

        $response = $this->uploadFile($this->model, [
            ArticlePage::FILEFIELD_DISK_KEY => [
                'nl' => [
                    'uploads' => [
                        [
                            'id' => 'xxx',
                            'path' => Storage::path('test/image-temp-name-nl.png'),
                            'originalName' => 'image-nl.png',
                            'mimeType' => 'image/png',
                            'fieldValues' => [],
                        ],
                    ],
                ],
                'en' => [],
            ],
        ]);

        app(FileApplication::class)->isolateAsset(
            $this->model->modelReference()->get(),
            ArticlePage::FILEFIELD_DISK_KEY,
            'nl',
            $this->model->asset(ArticlePage::FILEFIELD_DISK_KEY, 'nl')->id,
        );

        $media = $this->model->fresh()->asset(ArticlePage::FILEFIELD_DISK_KEY)->media->first();
        $this->assertEquals('secondMediaDisk', $media->disk);
        $this->assertEquals($this->getTempDirectory('media2/'.$media->id.'/'.$media->file_name), $media->getPath());
    }

    public function test_it_can_detach_and_isolate_asset_with_custom_asset_type()
    {
        config()->set('thinktomorrow.assetlibrary.types', [
            'custom' => CustomAsset::class,
        ]);

        UploadedFile::fake()->image('image-nl.png', '10', '80')->storeAs('test', 'image-temp-name-nl.png');

        $response = $this->uploadFile($this->model, [
            ArticlePage::FILEFIELD_ASSETTYPE_KEY => [
                'nl' => [
                    'uploads' => [
                        [
                            'id' => 'xxx',
                            'path' => Storage::path('test/image-temp-name-nl.png'),
                            'originalName' => 'image-nl.png',
                            'mimeType' => 'image/png',
                            'fieldValues' => [],
                        ],
                    ],
                ],
                'en' => [],
            ],
        ]);

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
