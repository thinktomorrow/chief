<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\Fakes\UploadMediaManager;

class UploadMediaTest extends TestCase
{
    use PageFormParams;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register('singles', UploadMediaManager::class, Single::class);

        Route::get('pages/{slug}', function () {
        })->name('pages.show');
    }

    /** @test */
    public function a_new_asset_can_be_uploaded()
    {
        // $this->disableExceptionHandling();
        $page = Single::create(['slug' => 'test']);

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
    public function required_asset_uploads_can_be_validated()
    {
        $page = Single::create(['slug' => 'test']);

        config()->set(['app.fallback_locale' => 'nl']);

        // Upload asset
        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [

                ]
            ]));

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function required_asset_uploads_can_be_validated_with_existing_and_removing()
    {
        config()->set(['app.fallback_locale' => 'nl']);
        $page = Single::create(['slug' => 'test']);
        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);

        // Assert Image is there
        $this->assertTrue($page->hasFile(MediaType::HERO));
        $this->assertCount(1, $page->getAllFiles(MediaType::HERO));

        // Upload asset
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

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function asset_uploads_dimensions_can_be_validated()
    {
        $page = Single::create(['slug' => 'test']);

        config()->set(['app.fallback_locale' => 'nl']);

        // Upload asset
        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'new' => [
                            $this->dummySlimImagePayload(),
                            $this->dummySlimImagePayloadWithInvalidDimensions('tt-favicon.png'),
                        ]
                    ]
                ]
            ]));

            $response->assertSessionHasErrors();
    }

    /** @test */
    public function asset_uploads_dimensions_can_be_validated_after_editing_with_slim()
    {
        $page = Single::create(['slug' => 'test']);
        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);

        $existing_asset = $page->getAllFiles(MediaType::HERO)->first();

        // Replace asset
        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'replace' => [
                            $existing_asset->id => $this->dummySlimImagePayloadWithInvalidDimensions(),
                        ]
                    ]
                ]
        ]));

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function a_new_asset_can_be_uploaded_as_regular_file()
    {
        $page = Single::create();

        config()->set(['app.fallback_locale' => 'nl']);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'new' => [
                            $this->dummySlimImagePayload(),
                        ]
                    ],
                    MediaType::DOCUMENT => [
                        'new' => [
                            UploadedFile::fake()->create('fake.pdf')
                        ]
                    ]
                ]
            ]));

        $this->assertTrue($page->hasFile(MediaType::DOCUMENT));
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
        $this->disableExceptionHandling();
        $page = Single::create(['slug' => 'test']);
        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);
        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);

        // Assert Image is there
        $this->assertTrue($page->hasFile(MediaType::HERO));
        $this->assertCount(2, $page->getAllFiles(MediaType::HERO));

        // Remove asset
        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'replace' => [
                            $page->getAllFiles(MediaType::HERO)->first()->id => null,
                            $page->getAllFiles(MediaType::HERO)->last()->id => null,
                        ],
                        'delete' => [
                            $page->getAllFiles(MediaType::HERO)->first()->id,
                        ]
                    ]
                ]
            ]));

        // Assert Image is no longer there
        $this->assertTrue($page->fresh()->hasFile(MediaType::HERO));
        $this->assertCount(1, $page->fresh()->getAllFiles());
    }

    /** @test */
    public function assets_can_be_sorted()
    {
        $page = Single::create();
        $page->addFile(UploadedFile::fake()->image('image.png'), MediaType::HERO);
        $page->addFile(UploadedFile::fake()->image('image2.png'), MediaType::HERO);

        $images = $page->fresh()->getAllFiles(MediaType::HERO);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'filesOrder' => [
                    MediaType::HERO => $images->last()->id . ',' . $images->first()->id,
                ],
                'files' => [
                    MediaType::HERO => [
                        'replace' => [
                            $images->last()->id => null,
                            $images->first()->id => null,
                        ]
                    ]
                ]
            ]));

        $this->assertEquals([$images->last()->id, $images->first()->id], $page->fresh()->getAllFiles(MediaType::HERO)->pluck('id')->toArray());
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
}
