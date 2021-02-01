<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields\Media\ImageField;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\Tests\Unit\Fields\Media\Fakes\ImageFieldManager;

class ReplaceImageFieldValueTest extends ChiefTestCase
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

        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'thumb_image_trans', 'nl');
    }

    /** @test */
    public function it_can_replace_localized_images()
    {
        $existing_asset_nl = $this->page->assets('thumb_image_trans', 'nl')->first();

        $this->uploadImage('thumb_image_trans', [
            'nl' => [
                $existing_asset_nl->id => $this->dummySlimImagePayload('tt-favicon-nl.png'),
            ],
            'en' => [
                $this->dummySlimImagePayload('tt-favicon-en.png'), // new
            ]
        ]);

        $this->assertEquals('tt-favicon-nl.png', $this->page->fresh()->asset('thumb_image_trans', 'nl')->filename());
        $this->assertEquals('tt-favicon-en.png', $this->page->fresh()->asset('thumb_image_trans', 'en')->filename());
    }

    /** @test */
    public function an_asset_can_be_replaced()
    {
        $existing_asset = $this->page->assets('thumb_image_trans')->first();

        // Replace asset
        $this->uploadImage('thumb_image_trans', [
            'nl' => [
                $existing_asset->id => $this->dummySlimImagePayload(),
            ],
        ]);

        // Assert replacement took place
        $this->assertCount(1, $this->page->fresh()->assets('thumb_image_trans'));
        $this->assertCount(2, Asset::all());

        $this->assertStringContainsString('tt-favicon.png', $this->page->fresh()->asset('thumb_image_trans', 'nl')->filename());
    }

    /** @test */
    public function an_asset_can_be_replaced_alongside_invalid_values()
    {
        $existing_asset = $this->page->assets('thumb_image_trans')->first();

        $this->uploadImage('thumb_image_trans', [
            'nl' => [
                $existing_asset->id => $this->dummySlimImagePayload(),
                null => null
            ],
        ]);

        // Assert replacement took place
        $this->assertCount(1, $this->page->fresh()->assets('thumb_image_trans'));
        $this->assertCount(2, Asset::all());

        $this->assertStringContainsString('tt-favicon.png', $this->page->fresh()->asset('thumb_image_trans', 'nl')->filename());
    }

    /** @test */
    public function an_replacing_asset_that_is_already_attached_is_passed_with_the_same_id_value()
    {
        $existing_asset = $this->page->assets('thumb_image_trans')->first();

        $this->uploadImage('thumb_image_trans', [
            'nl' => [
                $existing_asset->id => $existing_asset->id
            ],
        ]);

        $this->assertCount(1, $this->page->fresh()->assets('thumb_image_trans'));
    }

}
