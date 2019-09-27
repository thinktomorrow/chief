<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
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

        $this->assertTrue($page->fresh()->hasFile(MediaType::HERO));
        $this->assertCount(1, $page->fresh()->getAllFiles(MediaType::HERO));
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

        $this->assertTrue($module->fresh()->hasFile(MediaType::HERO));
        $this->assertCount(1, $module->fresh()->getAllFiles(MediaType::HERO));
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

        $this->assertTrue($page->fresh()->hasFile(MediaType::DOCUMENT));
        $this->assertCount(1, $page->getAllFiles(MediaType::DOCUMENT));
    }

    /** @test */
    public function an_asset_can_be_replaced()
    {
        $page = Single::create();
        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);

        $existing_asset = $page->getAllFiles(MediaType::HERO)->first();

        // Replace asset
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'replace' => [
                            $existing_asset->id => $this->dummySlimImagePayload(),
                        ]
                    ]
                ]
            ]));

        // Assert replacement took place
        $this->assertCount(1, $page->fresh()->getAllFiles(MediaType::HERO));
        $this->assertStringContainsString('tt-favicon.png', $page->fresh()->getFileUrl(MediaType::HERO));
    }

    /** @test */
    public function an_asset_can_be_removed()
    {
        $page = Single::create();
        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);

        // Assert Image is there
        $this->assertTrue($page->hasFile(MediaType::HERO));
        $this->assertCount(1, $page->getAllFiles(MediaType::HERO));

        // Remove asset
        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'delete' => [
                            $page->getAllFiles(MediaType::HERO)->first()->id,
                        ]
                    ]
                ]
            ]));

        // Assert Image is no longer there
        $this->assertFalse($page->fresh()->hasFile(MediaType::HERO));
        $this->assertCount(0, $page->fresh()->getAllFiles());
    }

    /** @test */
    public function an_asset_can_be_removed_and_uploaded()
    {
        $page = Single::create();
        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);

        // Assert Image is there
        $this->assertTrue($page->hasFile(MediaType::HERO));
        $this->assertCount(1, $page->getAllFiles(MediaType::HERO));

        // Remove asset
        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), [
                'files' => [
                    MediaType::HERO => [
                        'delete' => [
                            $page->getAllFiles(MediaType::HERO)->first()->id,
                        ],
                        'new' => [
                            UploadedFile::fake()->image('image.png')
                        ]
                    ],
                ],
            ]);

        $this->assertCount(1, $page->fresh()->getAllFiles());
    }

    /** @test */
    public function it_can_upload_image_with_uppercased_extension()
    {
        // Currently uploaded a xxx.JPEG fails retrieval as the source by Slim
        // TODO: this is something that should be provided by Assetlibrary
        $this->markTestIncomplete();

        $page = Single::create();

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'new' => [
                            $this->dummySlimImagePayload('tt-favicon.PNG'),
                        ]
                    ]
                ]
            ]));

        $this->assertEquals('tt-favicon.png', $page->getFilename(MediaType::HERO));
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

        $this->assertEquals('tt-favicon-nl.png', $page->getFilename('seo_image', 'nl'));
        $this->assertEquals('tt-favicon-en.png', $page->getFilename('seo_image', 'en'));
    }

    /** @test */
    public function it_can_replace_translatable_images()
    {
        app(Register::class)->register(PageManager::class, Single::class);
        $page = Single::create();
        $page->addFile(UploadedFile::fake()->image('image.png'), 'seo_image', 'nl');

        $existing_asset_nl = $page->getAllFiles('seo_image', 'nl')->first();

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

        $this->assertEquals('tt-favicon-nl.png', $page->fresh()->getFilename('seo_image', 'nl'));
        $this->assertEquals('tt-favicon-en.png', $page->fresh()->getFilename('seo_image', 'en'));
    }

    /** @test */
    public function it_can_remove_translatable_images()
    {
        app(Register::class)->register(PageManager::class, Single::class);
        $page = Single::create();
        $page->addFile(UploadedFile::fake()->image('image.png'), 'seo_image', 'en');

        $existing_asset_en = $page->getAllFiles('seo_image', 'en')->first();

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

        $this->assertEquals('tt-favicon-nl.png', $page->fresh()->getFilename('seo_image', 'nl'));
        $this->assertEquals('tt-favicon-nl.png', $page->fresh()->getFilename('seo_image', 'en'));
    }

    /** @test */
    public function assets_can_be_sorted()
    {
        $this->disableExceptionHandling();
        $page = Single::create();
        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);
        $page->addFile(UploadedFile::fake()->image('image2.png'), MediaType::HERO);

        $images = $page->fresh()->getAllFiles(MediaType::HERO);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'filesOrder' => [
                    'nl' => [
                        'files-'.MediaType::HERO => $images->last()->id . ',' . $images->first()->id,
                    ]
                ]
            ]));

        $assetIds = $page->fresh()->getAllFiles(MediaType::HERO)->pluck('id')->toArray();

        $this->assertEquals([$images->last()->id, $images->first()->id], $assetIds);
    }

    /** @test */
    public function localized_assets_can_be_sorted()
    {
        $this->disableExceptionHandling();
        $page = Single::create();
        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO, 'nl');
        $page->addFile(UploadedFile::fake()->image('image2.png'), MediaType::HERO, 'nl');
        $page->addFile(UploadedFile::fake()->image('image3.png'), MediaType::HERO, 'en');
        $page->addFile(UploadedFile::fake()->image('image4.png'), MediaType::HERO, 'en');

        $images = $page->assets()->get();
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'filesOrder' => 
                [
                    'nl' => [
                        'files-' . MediaType::HERO => $images[1]->id . ',' . $images[0]->id,
                    ],
                    'en' => [
                        'files-' . MediaType::HERO => $images[3]->id . ',' . $images[2]->id
                    ]
                ]
            ]));

        $newImagesSorted = $page->fresh()->assets()->get();

        $this->assertEquals($images[1]->id, $newImagesSorted[0]->id);
        $this->assertEquals($images[0]->id, $newImagesSorted[1]->id);
        $this->assertEquals($images[3]->id, $newImagesSorted[2]->id);
        $this->assertEquals($images[2]->id, $newImagesSorted[3]->id);
    }
}
