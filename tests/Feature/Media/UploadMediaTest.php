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
        $this->disableExceptionHandling();
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
    public function uploading_an_asset_for_invalid_locale_throws_error()
    {
        $this->disableExceptionHandling();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A valid files entry should have a key of either [new,replace,delete]. Instead mew is given.');

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
