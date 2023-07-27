<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

class UpdateFileFieldTest extends ChiefTestCase
{
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

        parent::tearDown();
    }

    public function test_it_can_store_uploads()
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
                        'fieldValues' => [
                            'caption' => 'I belong to this file',
                        ],
                    ],
                ],
            ],
        ]);
        $this->assertCount(1, $this->model->assets('thumb'));
        $this->assertEquals('image.png', $this->model->asset('thumb', 'nl')->getFileName());
        $this->assertEquals('I belong to this file', $this->model->asset('thumb', 'nl')->getPivotData('caption'));
    }

    public function test_it_can_store_uploads_per_locale()
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
            'en' => [
                'uploads' => [
                    [
                        'id' => 'xxx',
                        'path' => Storage::path('test/image-temp-name.png'),
                        'originalName' => 'image-en.png',
                        'mimeType' => 'image/png',
                    ],
                ],
            ],
        ]);

        $this->assertCount(2, $this->model->assets('thumb', null));
        $this->assertCount(1, $this->model->assets('thumb', 'nl'));
        $this->assertCount(1, $this->model->assets('thumb', 'en'));
        $this->assertEquals('image.png', $this->model->asset('thumb', 'nl')->getFileName());
        $this->assertEquals('image-en.png', $this->model->asset('thumb', 'en')->getFileName());
    }

    public function test_it_can_attach_assets()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $this->saveFileField($this->resource, $this->model, 'thumb', [
            'nl' => [
                'attach' => [
                    ['id' => $asset->id],
                ],
            ],
        ]);

        $this->assertCount(1, $this->model->assets('thumb'));
        $this->assertEquals('image.png', $this->model->asset('thumb')->getFileName());
    }

    public function test_it_should_not_attach_already_attached_assets()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $this->saveFileField($this->resource, $this->model, 'thumb', [
            'nl' => [
                'attach' => [
                    ['id' => $asset->id],
                ],
            ],
        ]);

        $this->assertCount(1, $this->model->assets('thumb'));
        $this->assertEquals('image.png', $this->model->asset('thumb')->getFileName());

        $this->saveFileField($this->resource, $this->model, 'thumb', [
            'nl' => [
                'attach' => [
                    ['id' => $asset->id],
                ],
            ],
        ]);

        $this->model->refresh();
        $this->assertCount(1, $this->model->assets('thumb'));
    }

    public function test_it_can_detach_assets()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        app(AddAsset::class)->handle($this->model, $asset, 'thumb', 'nl', 0, []);

        $this->assertCount(1, $this->model->assets('thumb'));

        $this->saveFileField($this->resource, $this->model, 'thumb', [
            'nl' => [
                'queued_for_deletion' => [$asset->id],
            ],
        ]);

        $this->assertCount(0, $this->model->fresh()->assets('thumb'));
    }

    public function test_it_can_reorder_uploads()
    {
        UploadedFile::fake()->image('image.png')->storeAs('test', 'image-temp-name.png');
        UploadedFile::fake()->image('image2.png')->storeAs('test', 'image-temp-name-2.png');

        $this->saveFileField($this->resource, $this->model, 'thumb', [
            'nl' => [
                'uploads' => [
                    [
                        'id' => 'xxx',
                        'path' => Storage::path('test/image-temp-name.png'),
                        'originalName' => 'image.png',
                        'mimeType' => 'image/png',
                    ],
                    [
                        'id' => 'yyy',
                        'path' => Storage::path('test/image-temp-name-2.png'),
                        'originalName' => 'image2.png',
                        'mimeType' => 'image/png',
                    ],
                ],
                'order' => [
                    'yyy',
                    'xxx',
                ],
            ],
        ]);

        $this->assertCount(2, $this->model->assets('thumb', null));
        $this->assertEquals('image2.png', $this->model->assets('thumb')[0]->getFileName());
        $this->assertEquals('image.png', $this->model->assets('thumb')[1]->getFileName());
    }

    public function test_it_can_store_field_values_on_uploads()
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
                        'fieldValues' => [
                            'caption' => 'I belong to this file',
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertCount(1, $this->model->assets('thumb'));
        $this->assertEquals('I belong to this file', $this->model->asset('thumb')->getPivotData('caption'));
    }
}
