<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields\Media\FileField;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\ManagedModels\Media\MediaType;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;

class AddFileFieldValueTest extends ChiefTestCase
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
}
