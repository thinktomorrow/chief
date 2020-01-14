<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Fakes\MediaModule;
use Thinktomorrow\Chief\Tests\Fakes\UploadMediaManager;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\Fakes\UploadMediaModuleManager;

class UploadMediaTest extends TestCase
{
    use PageFormParams;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('app.fallback_locale', 'nl');

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(UploadMediaManager::class, Single::class);
        app(Register::class)->register(UploadMediaModuleManager::class, MediaModule::class);

        Route::get('pages/{slug}', function () {
        })->name('pages.show');
    }

    /** @test */
    public function a_new_asset_can_be_uploaded()
    {
        $page = Single::create();

        config()->set(['app.fallback_locale' => 'nl']);

        // Upload asset
        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'new' => [
                            $this->dummySlimImagePayload(),
                        ]
                    ]
                ]
            ]));

        $this->assertCount(1, $page->fresh()->assets(MediaType::HERO));
    }

    /** @test */
    public function uploading_a_new_asset_and_an_invalid_asset_doesnt_throw_an_error()
    {
        $page = Single::create();

        config()->set(['app.fallback_locale' => 'nl']);

        // Upload asset
        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'new' => [
                            $this->dummySlimImagePayload(),
                            null
                        ]
                    ]
                ]
            ]));

        $this->assertCount(1, $page->fresh()->assets(MediaType::HERO));
    }

    /** @test */
    public function a_new_asset_can_be_uploaded_to_a_module()
    {
        $module = Module::create(['slug' => 'foobar module']);

        config()->set(['app.fallback_locale' => 'nl']);

        // Upload asset
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['mediamodule', $module->id]), [
                'files' => [
                    MediaType::HERO => [
                        'new' => [
                            $this->dummySlimImagePayload(),
                        ]
                    ]
                ]
            ]);

        $this->assertCount(1, $module->fresh()->assets(MediaType::HERO));
    }

    /** @test */
    public function a_new_asset_can_be_uploaded_as_regular_file()
    {
        $page = Single::create();

        config()->set(['app.fallback_locale' => 'nl']);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::DOCUMENT => [
                        'nl' => [
                            'new' => [
                                UploadedFile::fake()->create('fake.pdf')
                            ]
                        ]
                    ]
                ]
            ]));

        $this->assertCount(1, $page->assets(MediaType::DOCUMENT));
    }

    /** @test */
    public function it_can_upload_translatable_images()
    {
        app(Register::class)->register(PageManager::class, Single::class);
        $page = Single::create();

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    'seo_image' => [
                        'nl' => [
                            'new' => [
                                $this->dummySlimImagePayload('tt-favicon-nl.png'),
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

        $this->assertEquals('tt-favicon-nl.png', $page->asset('seo_image', 'nl')->filename());
        $this->assertEquals('tt-favicon-en.png', $page->asset('seo_image', 'en')->filename());
    }

    /** @test */
    public function it_can_replace_translatable_images()
    {
        app(Register::class)->register(PageManager::class, Single::class);
        $page = Single::create();
        app(AddAsset::class)->add($page, UploadedFile::fake()->image('image.png'), 'seo_image', 'nl');

        $existing_asset_nl = $page->assets('seo_image', 'nl')->first();

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    'seo_image' => [
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

        $this->assertEquals('tt-favicon-nl.png', $page->fresh()->asset('seo_image', 'nl')->filename());
        $this->assertEquals('tt-favicon-en.png', $page->fresh()->asset('seo_image', 'en')->filename());
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
                'files' => [
                    'seo_image' => [
                        'nl' => [
                            'new' => [
                                $this->dummySlimImagePayload('tt-favicon-nl.png'),
                            ]
                        ],
                        'en' => [
                            'delete' => [
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
    public function assets_can_be_sorted()
    {
        $page = Single::create();
        app(AddAsset::class)->add($page, UploadedFile::fake()->image('image.png'), MediaType::HERO, 'nl');
        app(AddAsset::class)->add($page, UploadedFile::fake()->image('image2.png'), MediaType::HERO, 'nl');

        $images = $page->fresh()->assets(MediaType::HERO);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'filesOrder' => [
                    'nl' => [
                        'files-'.MediaType::HERO => $images->last()->id . ',' . $images->first()->id,
                    ]
                ]
            ]));

        $assetIds = $page->fresh()->assets(MediaType::HERO)->pluck('id')->toArray();

        $this->assertEquals([$images->last()->id, $images->first()->id], $assetIds);
    }

    /** @test */
    public function localized_assets_can_be_sorted()
    {
        $page = Single::create();
        app(AddAsset::class)->add($page, UploadedFile::fake()->image('image.png'), MediaType::HERO, 'nl');
        app(AddAsset::class)->add($page, UploadedFile::fake()->image('image2.png'), MediaType::HERO, 'nl');
        app(AddAsset::class)->add($page, UploadedFile::fake()->image('image3.png'), MediaType::HERO, 'en');
        app(AddAsset::class)->add($page, UploadedFile::fake()->image('image4.png'), MediaType::HERO, 'en');

        $nl_images = $page->assets(MediaType::HERO, 'nl');
        $en_images = $page->assets(MediaType::HERO, 'en');

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'filesOrder' =>
                [
                    'nl' => [
                        'files-' . MediaType::HERO => $nl_images[1]->id . ',' . $nl_images[0]->id,
                    ],
                    'en' => [
                        'files-' . MediaType::HERO => $en_images[3]->id . ',' . $en_images[2]->id
                    ]
                ]
            ]));

        $nl_newImagesSorted = $page->refresh()->assets(MediaType::HERO, 'nl');
        $en_newImagesSorted = $page->assets(MediaType::HERO, 'en');

        $this->assertEquals($nl_images[1]->id, $nl_newImagesSorted[0]->id);
        $this->assertEquals($nl_images[0]->id, $nl_newImagesSorted[1]->id);
        $this->assertEquals($en_images[3]->id, $en_newImagesSorted[2]->id);
        $this->assertEquals($en_images[2]->id, $en_newImagesSorted[3]->id);
    }

    /** @test */
    public function an_existing_asset_can_be_added()
    {
        $page = Single::create();
        $existing_asset = AssetUploader::upload(UploadedFile::fake()->image('image.png'));

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'new' => [
                            $existing_asset->id,
                        ]
                    ]
                ]
            ]));

        // Assert replacement took place
        $this->assertCount(1, $page->fresh()->assets(MediaType::HERO));

        $this->assertEquals($existing_asset->url(), $page->fresh()->asset(MediaType::HERO, 'nl')->url());
    }

    /** @test */
    public function an_existing_asset_can_be_added_as_translation()
    {
        $page = Single::create();

        config()->set(['app.fallback_locale' => 'nl']);

        // Upload asset
        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'en' => [
                            'mew' => [
                                $this->dummySlimImagePayload(),
                            ]
                        ]
                    ]
                ]
            ]));
    }
}
