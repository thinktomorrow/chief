<?php

namespace Thinktomorrow\Chief\Tests\Feature\Media\ImageField;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\Feature\Media\Fakes\ImageFieldManager;

class ReplaceImageFieldValueTest extends TestCase
{
    use PageFormParams;

    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(ImageFieldManager::class, Single::class);

        $this->page = Single::create();
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), MediaType::HERO, 'nl');
    }

    /** @test */
    public function it_can_replace_localized_images()
    {
        $existing_asset_nl = $this->page->assets(MediaType::HERO, 'nl')->first();

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams([
                'images' => [
                    MediaType::HERO => [
                        'nl' => [
                            'replace' => [
                                $existing_asset_nl->id => $this->dummySlimImagePayload('tt-favicon-nl.png'),
                            ]
                        ],
                        'en' => [
                            'new' => [
                                $this->dummySlimImagePayload('tt-favicon-en.png'),
                            ]
                        ]
                    ]
                ]
            ]));

        $this->assertEquals('tt-favicon-nl.png', $this->page->fresh()->asset(MediaType::HERO, 'nl')->filename());
        $this->assertEquals('tt-favicon-en.png', $this->page->fresh()->asset(MediaType::HERO, 'en')->filename());
    }

    /** @test */
    public function an_asset_can_be_replaced()
    {
        $existing_asset = $this->page->assets(MediaType::HERO)->first();

        // Replace asset
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams([
                'images' => [
                    MediaType::HERO => [
                        'nl' => [
                            'replace' => [
                                $existing_asset->id => $this->dummySlimImagePayload(),
                            ]
                        ],
                    ]
                ]
            ]));

        // Assert replacement took place
        $this->assertCount(1, $this->page->fresh()->assets(MediaType::HERO));
        $this->assertCount(2, Asset::all());

        $this->assertStringContainsString('tt-favicon.png', $this->page->fresh()->asset(MediaType::HERO, 'nl')->filename());
    }

    /** @test */
    public function an_asset_can_be_replaced_alongside_invalid_values()
    {
        $existing_asset = $this->page->assets(MediaType::HERO)->first();

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams([
                'images' => [
                    MediaType::HERO => [
                        'nl' => [
                            'replace' => [
                                $existing_asset->id => $this->dummySlimImagePayload(),
                                null => null
                            ]
                        ],
                    ]
                ]
            ]));

        // Assert replacement took place
        $this->assertCount(1, $this->page->fresh()->assets(MediaType::HERO));
        $this->assertCount(2, Asset::all());

        $this->assertStringContainsString('tt-favicon.png', $this->page->fresh()->asset(MediaType::HERO, 'nl')->filename());
    }

}
