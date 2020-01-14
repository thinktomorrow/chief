<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Fakes\MediaModule;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Tests\Fakes\UploadMediaManager;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\Fakes\UploadMediaModuleManager;

class SortMediaTest extends TestCase
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
    public function assets_can_be_sorted()
    {
        $this->disableExceptionHandling();
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
        $this->disableExceptionHandling();
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
}
