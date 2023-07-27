<?php

namespace Thinktomorrow\Chief\Forms\Tests\File;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;

class AddFileTest extends ChiefTestCase
{
    use PageFormParams;
    use UploadsFile;

    private $model;
    private $manager;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setupAndCreateArticle();
        $this->manager = $this->manager($this->model);
    }

    public function test_it_can_add_existing_asset()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $response = $this->uploadFile($this->model, [
            'thumb' => [
                'nl' => [
                    'attach' => [
                        ['id' => $asset->id],
                    ],
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertCount(1, $this->model->assets());
    }

    public function test_it_can_add_a_new_file()
    {
        UploadedFile::fake()->image('image.png', '10', '80')->storeAs('test', 'image-temp-name.png');

        $response = $this->uploadFile($this->model, [
            'thumb' => [
                'nl' => [
                    'uploads' => [
                        [
                            'id' => 'xxx',
                            'path' => Storage::path('test/image-temp-name.png'),
                            'originalName' => 'image.png',
                            'mimeType' => 'image/png',
                            'fieldValues' => [],
                        ],
                    ],
                ],
                'en' => [],
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertCount(1, $this->model->assets('thumb'));
    }

    public function test_adding_same_existing_file_twice_will_only_add_it_once()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $response = $this->uploadFile($this->model, [
            'thumb' => [
                'nl' => [
                    'attach' => [
                        ['id' => $asset->id],
                    ],
                ],
            ],
        ]);

        $response = $this->uploadFile($this->model, [
            'thumb' => [
                'nl' => [
                    'attach' => [
                        ['id' => $asset->id],
                    ],
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertCount(1, $this->model->assets());
    }

    public function test_it_can_upload_translatable_files()
    {
        UploadedFile::fake()->image('image-nl.png', '10', '80')->storeAs('test', 'image-temp-name-nl.png');
        UploadedFile::fake()->image('image-en.png', '10', '80')->storeAs('test', 'image-temp-name-en.png');

        $response = $this->uploadFile($this->model, [
            'thumb' => [
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
                'en' => [
                    'uploads' => [
                        [
                            'id' => 'xxx',
                            'path' => Storage::path('test/image-temp-name-en.png'),
                            'originalName' => 'image-en.png',
                            'mimeType' => 'image/png',
                            'fieldValues' => [],
                        ],
                    ],
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertCount(2, $this->model->assets('thumb', null));
        $this->assertEquals('image-nl.png', $this->model->asset('thumb', 'nl')->getFileName());
        $this->assertEquals('image-en.png', $this->model->asset('thumb', 'en')->getFileName());
    }

    public function test_it_can_add_a_new_file_on_another_disk()
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

        $response->assertSessionHasNoErrors();
        $this->assertCount(1, $this->model->assets(ArticlePage::FILEFIELD_DISK_KEY));
        $this->assertEquals('image-nl.png', $this->model->asset(ArticlePage::FILEFIELD_DISK_KEY, 'nl')->getFileName());

        $media = $this->model->asset(ArticlePage::FILEFIELD_DISK_KEY)->media->first();
        $this->assertEquals('secondMediaDisk', $media->disk);
        $this->assertEquals($this->getTempDirectory('media2/' . $media->id . '/' . $media->file_name), $media->getPath());
    }
}
