<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields\Media\ImageField;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;

class AddImageFieldValueTest extends ChiefTestCase
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
    public function it_can_have_an_image()
    {
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'images', 'nl');

        $this->assertCount(1, $this->page->assets());
    }

    /** @test */
    public function it_can_add_a_new_image()
    {
        $response = $this->uploadImage('thumb_image', [
            'nl' => [$this->dummySlimImagePayload('image.png', 'image/png', 150, 150)],
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertCount(1, $this->page->assets('thumb_image'));
    }

    /** @test */
    public function it_can_add_a_new_image_with_a_random_key()
    {
        $response = $this->uploadImage('thumb_image', [
            'nl' => [99 => $this->dummySlimImagePayload('image.png', 'image/png', 150, 150)],
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertCount(1, $this->page->assets('thumb_image'));
    }

    /** @test */
    public function an_existing_image_can_be_added()
    {
        $existing_asset = AssetUploader::upload(UploadedFile::fake()->image('image.png', 810, 810));

        $response = $this->uploadImage('thumb_image', [
            'nl' => [$existing_asset->id],
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertCount(1, $this->page->fresh()->assets('thumb_image', 'nl'));

        $this->assertEquals($existing_asset->url(), $this->page->fresh()->asset('thumb_image', 'nl')->url());
    }

    /** @test */
    public function adding_same_existing_image_twice_will_only_add_it_once()
    {
        $existing_asset = AssetUploader::upload(UploadedFile::fake()->image('image.png', 810, 810));

        $this->uploadImage('thumb_image', [
            'nl' => [$existing_asset->id, $existing_asset->id],
        ]);

        $this->assertCount(1, $this->page->fresh()->assets('thumb_image'));

        $this->assertEquals($existing_asset->url(), $this->page->fresh()->asset('thumb_image', 'nl')->url());
    }

    /** @test */
    public function it_can_upload_translatable_images()
    {
        $this->uploadImage('thumb_image', [
            'nl' => [
                $this->dummySlimImagePayload('tt-favicon-nl.png', 'image/png', 800, 800),
            ],
            'en' => [
                $this->dummySlimImagePayload('tt-favicon-en.png', 'image/png', 800, 800),
            ],
        ]);

        $this->assertEquals('tt-favicon-nl.png', $this->page->asset('thumb_image', 'nl')->filename());
        $this->assertEquals('tt-favicon-en.png', $this->page->asset('thumb_image', 'en')->filename());
    }
}
