<?php

namespace Thinktomorrow\Chief\Tests\Feature\Media\ImageField;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\Feature\Media\Fakes\ImageFieldManager;

class DetachImageFieldValueTest extends TestCase
{
    use PageFormParams;

    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(ImageFieldManager::class, Single::class);
        $this->page = Single::create();

        // Upload existing asset
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), MediaType::HERO, 'nl');
    }

    /** @test */
    public function an_asset_can_be_removed()
    {
        $this->assertCount(1, $this->page->assets(MediaType::HERO));

        $this->detachImageRequest([
            'nl' => [
                'detach' => [
                    $this->page->assets(MediaType::HERO)->first()->id,
                ]
            ],
        ]);

        $this->assertCount(0, $this->page->fresh()->assets());
        $this->assertCount(1, Asset::all());
    }

    /** @test */
    public function an_image_can_be_removed_alongside_invalid_values()
    {
        $this->detachImageRequest([
            'nl' => [
                'detach' => [
                    $this->page->assets(MediaType::HERO)->first()->id,
                    null
                ]
            ],
        ]);

        $this->assertCount(0, $this->page->fresh()->assets());
        $this->assertCount(1, Asset::all());
    }

    /** @test */
    public function an_asset_can_be_removed_and_uploaded()
    {
        $this->detachImageRequest([
            'nl' => [
                'detach' => [
                    $this->page->assets(MediaType::HERO)->first()->id,
                ],
                'new' => [
                    $this->dummySlimImagePayload('image.jpg','image/jpeg'),
                ]
            ],
        ]);

        $this->assertCount(1, $this->page->fresh()->assets());
        $this->assertEquals('image.jpg', $this->page->refresh()->asset(MediaType::HERO, 'nl')->filename());
    }

    /** @test */
    public function it_can_remove_translatable_images()
    {
        app(Register::class)->register(PageManager::class, Single::class);
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'seo_image', 'en');

        $existing_asset_en = $this->page->assets('seo_image', 'en')->first();

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams([
                'images' => [
                    'seo_image' => [
                        'nl' => [
                            'new' => [
                                $this->dummySlimImagePayload('tt-favicon-nl.png'),
                            ]
                        ],
                        'en' => [
                            'detach' => [
                                $existing_asset_en->id,
                            ]
                        ]
                    ]
                ]
            ]));

        $this->assertEquals('tt-favicon-nl.png', $this->page->refresh()->asset('seo_image', 'nl')->filename());
        $this->assertEquals('tt-favicon-nl.png', $this->page->asset('seo_image', 'en')->filename());
    }

    private function detachImageRequest($payload)
    {
        return $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), [
                'images' => [
                    MediaType::HERO => $payload,
                ],
            ]);
    }
}
