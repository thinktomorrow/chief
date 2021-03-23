<?php

namespace Thinktomorrow\Chief\Tests\Feature\Media\FileField;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Management\Register;
use Illuminate\Foundation\Testing\TestResponse;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\Tests\Feature\Media\Fakes\MediaModule;
use Thinktomorrow\Chief\Tests\Feature\Media\Fakes\FileFieldManager;
use Thinktomorrow\Chief\Tests\Feature\Media\Fakes\FileFieldModuleManager;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;

class AddFileFieldValueTest extends TestCase
{
    const FILEFIELD_KEY = 'fake-file';
    const FILEFIELD_DISK_KEY = 'file-on-other-disk';

    use PageFormParams;

    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(FileFieldManager::class, Single::class);
        $this->page = Single::create();
    }

    /** @test */
    public function it_can_have_a_file()
    {
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'images', 'nl');

        $this->assertCount(1, $this->page->assets());
    }

    /** @test */
    public function it_can_add_a_new_file()
    {
        $this->disableExceptionHandling();
        $response = $this->newFileRequest([
            'nl' => [
                $this->dummyUploadedFile('tt-document.txt'),
            ],
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertCount(1, $this->page->assets(static::FILEFIELD_KEY));
    }

    /** @test */
    public function a_module_can_add_a_new_file()
    {
        app(Register::class)->register(FileFieldModuleManager::class, MediaModule::class);
        $module = MediaModule::create(['slug' => 'fake-module']);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['mediamodule', $module->id]), [
                'files' => [
                    static::FILEFIELD_KEY => [
                        'nl' => [
                            $this->dummyUploadedFile('tt-document.pdf'),
                        ],
                    ]
                ]
            ]);

        $this->assertCount(1, $module->fresh()->assets(static::FILEFIELD_KEY));
    }

    /** @test */
    public function an_existing_file_can_be_added()
    {
        $existing_asset = AssetUploader::upload(UploadedFile::fake()->image('image.png', 810, 810));

        $response = $this->newFileRequest([
            'nl' => [
                $existing_asset->id,
            ],
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertCount(1, $this->page->fresh()->assets(static::FILEFIELD_KEY, 'nl'));

        $this->assertEquals($existing_asset->url(), $this->page->fresh()->asset(static::FILEFIELD_KEY, 'nl')->url());
    }

    /** @test */
    public function it_can_upload_a_file_via_redactor_wysiwyg()
    {
        $response = $this->asAdmin()->post(route('chief.back.managers.media.upload', ['singles', $this->page->id]), [
            'files'   => [
                [
                    'data' => $this->dummyBase64Payload(),
                    'filename' => 'image.png',
                ]
            ],
            'locale' => 'nl',
        ]);

        $assets = $this->page->assets(MediaType::CONTENT, 'nl');
        $this->assertCount(1, $assets);

        $response->assertStatus(201)
            ->assertJson([
                "file-" . $assets->first()->id => [
                    "url" => $this->page->asset(MediaType::CONTENT)->url(),
                    "id"  => $assets->first()->id,
                ],
            ]);
    }

    /** @test */
    public function adding_same_existing_file_twice_will_only_add_it_once()
    {
        $existing_asset = AssetUploader::upload(UploadedFile::fake()->image('image.png', 810, 810));

        $response = $this->newFileRequest([
            'nl' => [
                $existing_asset->id,
                $existing_asset->id,
            ],
        ]);

        $response->assertSessionHasErrors();
        $this->assertStringContainsString('Een van de fotos die je uploadde bestond al.', session()->get('errors')->first());

        $this->assertCount(1, $this->page->fresh()->assets(static::FILEFIELD_KEY));

        $this->assertEquals($existing_asset->url(), $this->page->fresh()->asset(static::FILEFIELD_KEY, 'nl')->url());
    }

    /** @test */
    public function it_can_upload_translatable_files()
    {
        $this->newFileRequest([
            'nl' => [
                UploadedFile::fake()->image('tt-favicon-nl.png'),
            ],
            'en' => [
                UploadedFile::fake()->image('tt-favicon-en.png'),
            ]
        ]);

        $this->assertEquals('tt-favicon-nl.png', $this->page->asset(static::FILEFIELD_KEY, 'nl')->filename());
        $this->assertEquals('tt-favicon-en.png', $this->page->asset(static::FILEFIELD_KEY, 'en')->filename());
    }

    /** @test */
    public function it_can_add_a_new_file_on_another_disk()
    {
        $response = $this->newFileRequest([
            'nl' => [
                $this->dummyUploadedFile('tt-document.txt'),
            ],
        ], static::FILEFIELD_DISK_KEY);

        $response->assertSessionHasNoErrors();

        $this->assertCount(1, $this->page->assets(static::FILEFIELD_DISK_KEY));

        $media = $this->page->asset(static::FILEFIELD_DISK_KEY)->media->first();
        $this->assertEquals('secondMediaDisk', $media->disk);
        $this->assertEquals($this->getTempDirectory('media2/' . $media->id.'/'.$media->file_name), $media->getPath());
    }

    private function newFileRequest($payload, $key = null): TestResponse
    {
        $key = $key ?: static::FILEFIELD_KEY;

        return $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams([
                'files' => [
                    $key => $payload,
                ],
            ]));
    }
}
