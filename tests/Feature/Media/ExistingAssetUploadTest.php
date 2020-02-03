<?php

namespace Thinktomorrow\Chief\Tests\Feature\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Fakes\MediaModule;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\Tests\Fakes\UploadMediaManager;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\Fakes\UploadMediaModuleManager;

class ExistingAssetUploadTest extends TestCase
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

        $this->assertCount(1, $page->fresh()->assets(MediaType::HERO));

        $this->assertEquals($existing_asset->url(), $page->fresh()->asset(MediaType::HERO, 'nl')->url());
    }


    /** @test */
    public function an_existing_asset_can_not_be_added_more_than_once()
    {
        $page = Single::create();
        $existing_asset = AssetUploader::upload(UploadedFile::fake()->image('image.png'));

        $response = $this->asAdmin()->followingRedirects()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'new' => [
                            $existing_asset->id,
                            $existing_asset->id,
                        ]
                    ]
                ]
            ]));

        $response->assertSee('Een van de fotos die je uploadde bestond al.');

        $this->assertCount(1, $page->fresh()->assets(MediaType::HERO));

        $this->assertEquals($existing_asset->url(), $page->fresh()->asset(MediaType::HERO, 'nl')->url());
    }

    /** @test */
    public function an_existing_asset_can_be_added_as_translation()
    {
        $page = Single::create();
        $existing_asset = AssetUploader::upload(UploadedFile::fake()->image('image.png'));

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'en' => [
                            'new' => [
                                $existing_asset->id
                            ]
                        ]
                    ]
                ]
            ]));

        $this->assertCount(1, $page->fresh()->assets(MediaType::HERO, 'en'));

        $this->assertEquals($existing_asset->url(), $page->fresh()->asset(MediaType::HERO, 'en')->url());
    }

    /** @test */
    public function it_can_link_an_asset_to_multiple_pages()
    {
        $page = Single::create();
        $page2 = Single::create();
        $existing_asset = AssetUploader::upload(UploadedFile::fake()->image('image.png'));

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'en' => [
                            'new' => [
                                $existing_asset->id
                            ]
                        ]
                    ]
                ]
            ]));

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page2->id]), $this->validUpdatePageParams([
                'trans' => [
                    'nl' => [
                        'slug' => 'page 2'
                    ]
                ],
                'url-slugs' => [
                    'nl' => 'slug',
                    'en' => 'slug',
                ],
                'files' => [
                    MediaType::HERO => [
                        'en' => [
                            'new' => [
                                $existing_asset->id
                            ]
                        ]
                    ]
                ]
            ]));

        $this->assertCount(1, $page->refresh()->assets(MediaType::HERO, 'en'));
        $this->assertCount(1, $page2->refresh()->assets(MediaType::HERO, 'en'));

        $this->assertEquals($existing_asset->url(), $page->asset(MediaType::HERO, 'en')->url());
        $this->assertEquals($existing_asset->url(), $page2->asset(MediaType::HERO, 'en')->url());
    }

}
