<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields\File;

use function app;
use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;

class DeleteFileTest extends ChiefTestCase
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
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'thumb_trans', 'nl');
        $this->assertCount(1, $this->page->assets('thumb_trans'));

        $this->uploadFile('thumb_trans', [
            'nl' => [
                $this->page->assets('thumb_trans')->first()->id => null,
            ],
        ]);

        $this->assertCount(0, $this->page->fresh()->assets());
        $this->assertCount(1, Asset::all());
    }

    /** @test */
    public function an_image_can_be_removed_alongside_invalid_values()
    {
        // Upload existing asset
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'thumb_trans', 'nl');

        $this->uploadFile('thumb_trans', [
            'nl' => [
                $this->page->assets('thumb_trans')->first()->id => null,
                null => null,
            ],
        ]);

        $this->assertCount(0, $this->page->fresh()->assets());
        $this->assertCount(1, Asset::all());
    }

    /** @test */
    public function an_asset_can_be_removed_and_uploaded()
    {
        // Upload existing asset
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'thumb_trans', 'nl');

        $this->uploadFile('thumb_trans', [
            'nl' => [
                    $this->page->assets('thumb_trans')->first()->id => null,
                    UploadedFile::fake()->image('image.jpg'), // new
            ],
        ]);

        $this->assertCount(1, $this->page->fresh()->assets());
        $this->assertEquals('image.jpg', $this->page->refresh()->asset('thumb_trans', 'nl')->filename());
    }

    /** @test */
    public function it_can_remove_translatable_images()
    {
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'thumb_trans', 'en');

        $existing_asset_en = $this->page->assets('thumb_trans', 'en')->first();

        $this->uploadFile('thumb_trans', [
            'nl' => [
                UploadedFile::fake()->image('tt-favicon-nl.png'), // new
            ],
            'en' => [
                $existing_asset_en->id => null, // detach
            ],
        ]);

        $this->assertEquals('tt-favicon-nl.png', $this->page->refresh()->asset('thumb_trans', 'nl')->filename());
        $this->assertEquals('tt-favicon-nl.png', $this->page->asset('thumb_trans', 'en')->filename());
    }
}
