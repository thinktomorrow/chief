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
use Thinktomorrow\Chief\Tests\Fakes\ImageFieldManagerWithoutValidation;

class ReplaceMediaTest extends TestCase
{
    use PageFormParams;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('app.fallback_locale', 'nl');

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(ImageFieldManagerWithoutValidation::class, Single::class);
        app(Register::class)->register(UploadMediaModuleManager::class, MediaModule::class);

        Route::get('pages/{slug}', function () {
        })->name('pages.show');
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
                 'images' => [
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
    public function an_asset_can_be_replaced()
    {
        $this->disableExceptionHandling();
        $page = Single::create();
        app(AddAsset::class)->add($page, UploadedFile::fake()->image('image.png'), MediaType::HERO, 'nl');

        $existing_asset = $page->assets(MediaType::HERO)->first();

        // Replace asset
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
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
        $this->assertCount(1, $page->fresh()->assets(MediaType::HERO));
        $this->assertCount(2, Asset::all());

        $this->assertStringContainsString('tt-favicon.png', $page->fresh()->asset(MediaType::HERO, 'nl')->filename());
    }

    /** @test */
    public function an_asset_can_be_replaced_alongside_invalid_values()
    {
        $page = Single::create();
        app(AddAsset::class)->add($page, UploadedFile::fake()->image('image.png'), MediaType::HERO, 'nl');

        $existing_asset = $page->assets(MediaType::HERO)->first();

        // Replace asset
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
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
        $this->assertCount(1, $page->fresh()->assets(MediaType::HERO));
        $this->assertCount(2, Asset::all());

        $this->assertStringContainsString('tt-favicon.png', $page->fresh()->asset(MediaType::HERO, 'nl')->filename());
    }

}
