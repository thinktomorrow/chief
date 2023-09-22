<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Assets\App\FileHelper;
use Thinktomorrow\Chief\Forms\Tests\stubs\CustomAsset;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;

class FileApplicationTest extends ChiefTestCase
{
    use UploadsFile;

    private $model;
    private Resource $resource;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setUpAndCreateArticle();
        $this->resource = app(ArticlePageResource::class);
    }

    public function tearDown(): void
    {
        Storage::delete('test/image-temp-name.png');
        Storage::delete('test/image-second-temp-name.jpg');

        parent::tearDown();
    }

    public function test_it_can_update_field_values()
    {
        $this->uploadDefaultAsset();

        app(FileApplication::class)->updateAssociatedAssetData($this->model->modelReference()->get(), 'thumb', 'nl', $this->model->asset('thumb', 'nl')->id, [
            'caption' => 'I belong to this file',
        ]);

        $this->assertEquals('I belong to this file', $this->model->fresh()->asset('thumb', 'nl')->getData('caption'));
    }

    private function uploadDefaultAsset(string $filename = 'image.png', string $mimeType = 'image/png'): void
    {
        $tempName = Str::random() . '.' . FileHelper::getExtension($filename);
        UploadedFile::fake()->image($filename)->storeAs('test', $tempName);

        $this->uploadAsset(
            'test/' . $tempName,
            $filename,
            $mimeType,
            $this->model,
            $this->resource,
        );
    }

    private function uploadAsset(string $path, string $filename, string $mimeType, $model, $resource): void
    {

        $this->saveFileField($resource, $model, 'thumb', [
            'nl' => [
                'uploads' => [
                    [
                        'id' => 'xxx',
                        'path' => Storage::path($path),
                        'originalName' => $filename,
                        'mimeType' => $mimeType,
                    ],
                ],
            ],
        ]);
    }

    public function test_it_can_update_filename()
    {
        $this->uploadDefaultAsset();

        app(FileApplication::class)->updateFileName($this->model->asset('thumb', 'nl')->id, 'foobar.jpg');

        $this->assertEquals('foobar.jpg.png', $this->model->fresh()->asset('thumb', 'nl')->getFileName());
        $this->assertEquals('png', $this->model->fresh()->asset('thumb', 'nl')->getExtension());
    }

    public function test_it_can_replace_media()
    {
        UploadedFile::fake()->image('image.png')->storeAs('test', 'image-temp-name.png');
        $otherFile = UploadedFile::fake()->image('image-second.jpg');
        $otherFile->storeAs('test', 'image-second-temp-name.jpg');

        $this->saveFileField($this->resource, $this->model, 'thumb', [
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
        ]);

        app(FileApplication::class)->replaceMedia($this->model->asset('thumb', 'nl')->id, $otherFile);

        $this->assertEquals('image-second.jpg', $this->model->fresh()->asset('thumb', 'nl')->getFileName());
        $this->assertEquals('jpg', $this->model->fresh()->asset('thumb', 'nl')->getExtension());
        $this->assertCount(1, Asset::all());
        $this->assertCount(1, Media::all());
    }

    public function test_it_can_detach_and_isolate_asset()
    {
        $filename = 'image.png';
        $mimeType = 'image/png';

        $tempName = Str::random() . '.' . FileHelper::getExtension($filename);
        UploadedFile::fake()->image($filename)->storeAs('test', $tempName);

        $this->uploadAsset(
            'test/' . $tempName,
            $filename,
            $mimeType,
            $this->model,
            $this->resource,
        );

        $model2 = $this->setUpAndCreateArticle([], false);
        $this->uploadAsset(
            'test/' . $tempName,
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
        $this->assertEquals($this->getTempDirectory('media2/' . $media->id . '/' . $media->file_name), $media->getPath());
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
