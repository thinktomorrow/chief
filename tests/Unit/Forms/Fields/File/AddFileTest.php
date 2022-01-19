<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields\File;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Forms\Fields\Media\MediaType;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use function app;

class AddFileTest extends ChiefTestCase
{
    use PageFormParams;
    use UploadsFile;

    private $page;
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = $this->setupAndCreateArticle();
        $this->manager = $this->manager($this->page);
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
        $response = $this->uploadFile('thumb', [
            'nl' => [$this->dummyUploadedFile('tt-document.txt')],
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertCount(1, $this->page->assets('thumb'));
    }

    /** @test */
    public function it_can_add_a_new_image()
    {
        $response = $this->uploadFile('thumb_image', [
            'nl' => [$this->dummySlimImagePayload('image.png', 'image/png', 150, 150)],
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertCount(1, $this->page->assets('thumb_image'));
    }

    /** @test */
    public function an_existing_file_can_be_added()
    {
        $existing_asset = AssetUploader::upload(UploadedFile::fake()->image('image.png', 810, 810));

        $response = $this->uploadFile('thumb_trans', [
            'nl' => [
                $existing_asset->id,
            ],
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertCount(1, $this->page->fresh()->assets('thumb_trans', 'nl'));

        $this->assertEquals($existing_asset->url(), $this->page->fresh()->asset('thumb_trans', 'nl')->url());
    }

    /** @test */
    public function it_can_upload_a_file_via_redactor_wysiwyg()
    {
        $response = $this->asAdmin()->post($this->manager->route('asyncRedactorFileUpload', $this->page), [
            'files' => [
                [
                    'data' => $this->dummyBase64Payload(),
                    'filename' => 'image.png',
                ],
            ],
            'locale' => 'nl',
        ]);

        $assets = $this->page->assets(MediaType::CONTENT, 'nl');
        $this->assertCount(1, $assets);

        $response->assertStatus(201)
            ->assertJson([
                "file-" . $assets->first()->id => [
                    "url" => $this->page->asset(MediaType::CONTENT)->url(),
                    "id" => $assets->first()->id,
                ],
            ]);
    }

    /** @test */
    public function adding_same_existing_file_twice_will_only_add_it_once()
    {
        $existing_asset = AssetUploader::upload(UploadedFile::fake()->image('image.png', 810, 810));

        $this->uploadFile('thumb_trans', [
            'nl' => [
                $existing_asset->id,
                $existing_asset->id,
            ],
        ]);

        $this->assertCount(1, $this->page->fresh()->assets('thumb_trans'));

        $this->assertEquals($existing_asset->url(), $this->page->fresh()->asset('thumb_trans', 'nl')->url());
    }

    /** @test */
    public function it_can_upload_translatable_files()
    {
        $this->uploadFile('thumb_trans', [
            'nl' => [
                UploadedFile::fake()->image('tt-favicon-nl.png'),
            ],
            'en' => [
                UploadedFile::fake()->image('tt-favicon-en.png'),
            ],
        ]);

        $this->assertEquals('tt-favicon-nl.png', $this->page->asset('thumb_trans', 'nl')->filename());
        $this->assertEquals('tt-favicon-en.png', $this->page->asset('thumb_trans', 'en')->filename());
    }

    /** @test */
    public function it_can_add_a_new_file_on_another_disk()
    {
        $this->disableExceptionHandling();
        $response = $this->uploadFile(ArticlePage::FILEFIELD_DISK_KEY, [
            'nl' => [
                $this->dummyUploadedFile('tt-document.txt'),
            ],
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertCount(1, $this->page->assets(ArticlePage::FILEFIELD_DISK_KEY));

        $media = $this->page->asset(ArticlePage::FILEFIELD_DISK_KEY)->media->first();
        $this->assertEquals('secondMediaDisk', $media->disk);
        $this->assertEquals($this->getTempDirectory('media2/' . $media->id.'/'.$media->file_name), $media->getPath());
    }
}
