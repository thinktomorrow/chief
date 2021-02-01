<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields\Media\FileField;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\AssetLibrary\Application\AddAsset;

class ReplaceFileFieldValueTest extends ChiefTestCase
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

        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'thumb_trans', 'nl');
    }

    /** @test */
    public function it_can_replace_localized_images()
    {
        $existing_asset_nl = $this->page->assets('thumb_trans', 'nl')->first();

        $this->uploadFile('thumb_trans', [
            'nl' => [
                $existing_asset_nl->id => UploadedFile::fake()->image('tt-favicon-nl.png'), // replace
            ],
            'en' => [
                UploadedFile::fake()->image('tt-favicon-en.png'), // New
            ]
        ]);

        $this->assertEquals('tt-favicon-nl.png', $this->page->fresh()->asset('thumb_trans', 'nl')->filename());
        $this->assertEquals('tt-favicon-en.png', $this->page->fresh()->asset('thumb_trans', 'en')->filename());
    }

    /** @test */
    public function an_asset_can_be_replaced()
    {
        $existing_asset = $this->page->assets('thumb_trans')->first();

        // Replace asset
        $this->uploadFile('thumb_trans', [
            'nl' => [
                $existing_asset->id => $this->dummyUploadedFile('tt-document.pdf'),
            ],
        ]);

        // Assert replacement took place
        $this->assertCount(1, $this->page->fresh()->assets('thumb_trans'));
        $this->assertCount(2, Asset::all());

        $this->assertStringContainsString('tt-document.pdf', $this->page->fresh()->asset('thumb_trans', 'nl')->filename());
    }

    /** @test */
    public function an_asset_can_be_replaced_alongside_invalid_values()
    {
        $existing_asset = $this->page->assets('thumb_trans')->first();

        $this->uploadFile('thumb_trans', [
            'nl' => [
                $existing_asset->id => UploadedFile::fake()->image('tt-favicon.png'),
                null => null
            ],
        ]);

        // Assert replacement took place
        $this->assertCount(1, $this->page->fresh()->assets('thumb_trans'));
        $this->assertCount(2, Asset::all());

        $this->assertStringContainsString('tt-favicon.png', $this->page->fresh()->asset('thumb_trans', 'nl')->filename());
    }

}
