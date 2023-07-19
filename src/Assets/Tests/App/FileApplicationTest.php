<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

class FileApplicationTest extends ChiefTestCase
{
    private $model;
    private Resource $resource;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        $this->model = ArticlePage::create();
        $this->resource = app(ArticlePageResource::class);
    }

    protected function tearDown(): void
    {
        Storage::delete('test/image-temp-name.png');
        Storage::delete('test/image-second-temp-name.jpg');

        parent::tearDown();
    }

    public function test_it_can_update_field_values()
    {
        UploadedFile::fake()->image('image.png')->storeAs('test', 'image-temp-name.png');

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

        app(FileApplication::class)->updateAssociatedAssetData($this->model->modelReference()->get(), 'thumb', 'nl', $this->model->asset('thumb', 'nl')->id, [
            'caption' => 'I belong to this file',
        ]);

        $this->assertEquals('I belong to this file', $this->model->fresh()->asset('thumb', 'nl')->getData('caption'));
    }

    public function test_it_can_update_filename()
    {
        UploadedFile::fake()->image('image.png')->storeAs('test', 'image-temp-name.png');

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
}
