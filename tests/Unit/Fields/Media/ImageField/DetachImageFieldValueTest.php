<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields\Media\ImageField;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\Tests\Unit\Fields\Media\Fakes\ImageFieldManager;

class DetachImageFieldValueTest extends ChiefTestCase
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
    public function an_asset_can_be_removed()
    {
        // Upload existing asset
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'thumb_image_trans', 'nl');
        $this->assertCount(1, $this->page->assets('thumb_image_trans'));

        $this->uploadImage('thumb_image_trans', [
            'nl' => [
                $this->page->assets('thumb_image_trans')->first()->id => null,
            ],
        ]);

        $this->assertCount(0, $this->page->fresh()->assets());
        $this->assertCount(1, Asset::all());
    }

    /** @test */
    public function an_image_can_be_removed_alongside_invalid_values()
    {
        // Upload existing asset
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'thumb_image_trans', 'nl');

        $this->uploadImage('thumb_image_trans', [
            'nl' => [
                $this->page->assets('thumb_image_trans')->first()->id => null,
                null,
            ],
        ]);

        $this->assertCount(0, $this->page->fresh()->assets());
        $this->assertCount(1, Asset::all());
    }

    /** @test */
    public function an_asset_can_be_removed_and_uploaded()
    {
        // Upload existing asset
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'thumb_image_trans', 'nl');

        $this->uploadImage('thumb_image_trans', [
            'nl' => [
                $this->page->assets('thumb_image_trans')->first()->id => null,
                $this->dummySlimImagePayload('image.jpg','image/jpeg'),
            ],
        ]);

        $this->assertCount(1, $this->page->fresh()->assets());
        $this->assertEquals('image.jpg', $this->page->refresh()->asset('thumb_image_trans', 'nl')->filename());
    }

    /** @test */
    public function it_can_remove_translatable_images()
    {
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'thumb_image_trans', 'en');

        $existing_asset_en = $this->page->assets('thumb_image_trans', 'en')->first();

        $this->uploadImage('thumb_image_trans', [
            'nl' => [
                $this->dummySmallSlimImagePayload('tt-favicon-nl.png'), // new
            ],
            'en' => [
                $existing_asset_en->id => null, // detach
            ]
        ]);

        $this->assertEquals('tt-favicon-nl.png', $this->page->refresh()->asset('thumb_image_trans', 'nl')->filename());
        $this->assertEquals('tt-favicon-nl.png', $this->page->asset('thumb_image_trans', 'en')->filename());
    }
}
