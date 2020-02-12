<?php

namespace Thinktomorrow\Chief\Tests\Feature\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Fakes\MediaModule;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Tests\Fakes\FileFieldManager;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\Fakes\UploadMediaModuleManager;
use Thinktomorrow\Chief\Tests\Fakes\FileFieldManagerWithoutValidation;

class RemoveMediaTest extends TestCase
{
    use PageFormParams;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('app.fallback_locale', 'nl');

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(FileFieldManagerWithoutValidation::class, Single::class);
        app(Register::class)->register(UploadMediaModuleManager::class, MediaModule::class);

        Route::get('pages/{slug}', function () {
        })->name('pages.show');
    }

    /** @test */
    public function an_asset_can_be_removed()
    {
        $page = Single::create();
        app(AddAsset::class)->add($page, UploadedFile::fake()->image('image.png'), MediaType::HERO, 'nl');

        // Assert Image is there
        $this->assertCount(1, $page->assets(MediaType::HERO));

        // Remove asset
        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'nl' => [
                            'detach' => [
                                $page->assets(MediaType::HERO)->first()->id,
                            ]
                        ],
                    ]
                ]
            ]));

        // Assert Image is no longer there
        $this->assertCount(0, $page->fresh()->assets());
        $this->assertCount(1, Asset::all());
    }

    /** @test */
    public function an_asset_can_be_removed_alongside_invalid_values()
    {
        $page = Single::create();
        app(AddAsset::class)->add($page, UploadedFile::fake()->image('image.png'), MediaType::HERO, 'nl');

        // Assert Image is there
        $this->assertCount(1, $page->assets(MediaType::HERO));

        // Remove asset
        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'nl' => [
                            'detach' => [
                                $page->assets(MediaType::HERO)->first()->id,
                                null
                            ]
                        ],

                    ]
                ]
            ]));

        // Assert Image is no longer there
        $this->assertCount(0, $page->fresh()->assets());
        $this->assertCount(1, Asset::all());
    }

    /** @test */
    public function an_asset_can_be_removed_and_uploaded()
    {
        $page = Single::create();
        app(AddAsset::class)->add($page, UploadedFile::fake()->image('image.png'), MediaType::HERO, 'nl');

        // Assert Image is there
        $this->assertCount(1, $page->assets(MediaType::HERO));

        // Remove asset
        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), [
                'files' => [
                    MediaType::HERO => [
                        'nl' => [
                            'detach' => [
                                $page->assets(MediaType::HERO)->first()->id,
                            ],
                            'new' => [
                                UploadedFile::fake()->image('image.png')
                            ]
                        ],
                    ],
                ],
            ]);

        $this->assertCount(1, $page->fresh()->assets());
    }

    /** @test */
    public function it_can_remove_translatable_images()
    {
        app(Register::class)->register(PageManager::class, Single::class);
        $page = Single::create();
        app(AddAsset::class)->add($page, UploadedFile::fake()->image('image.png'), 'seo_image', 'en');

        $existing_asset_en = $page->assets('seo_image', 'en')->first();

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
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

        $this->assertEquals('tt-favicon-nl.png', $page->refresh()->asset('seo_image', 'nl')->filename());
        $this->assertEquals('tt-favicon-nl.png', $page->asset('seo_image', 'en')->filename());
    }

    /** @test */
    public function removing_a_model_with_asset_unlinks_the_asset()
    {
        $page = Single::create();
        app(AddAsset::class)->add($page, UploadedFile::fake()->image('image.png'), MediaType::HERO, 'nl');

        $this->asAdmin()
            ->delete('/admin/manage/singles/'.$page->id);

        $this->assertCount(1, Asset::all());
    }
}
