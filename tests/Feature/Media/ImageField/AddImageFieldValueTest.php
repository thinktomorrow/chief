<?php

namespace Thinktomorrow\Chief\Tests\Feature\Media\ImageField;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Management\Register;
use Illuminate\Foundation\Testing\TestResponse;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\Chief\Tests\Feature\Media\Fakes\MediaModule;
use Thinktomorrow\Chief\Tests\Feature\Media\Fakes\ImageFieldModuleManager;
use Thinktomorrow\Chief\Tests\Feature\Media\Fakes\ImageFieldManagerWithValidation;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;

class AddImageFieldValueTest extends TestCase
{
    use PageFormParams;

    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(ImageFieldManagerWithValidation::class, Single::class);
        $this->page = Single::create();
    }

    /** @test */
    public function it_can_have_an_image()
    {
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('image.png'), 'images', 'nl');

        $this->assertCount(1, $this->page->assets());
    }

    /** @test */
    public function it_can_add_a_new_image()
    {
        $response = $this->newImageRequest([
            'nl' => [
                $this->dummySlimImagePayload('image.png', 'image/png', 150, 150),
            ],
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertCount(1, $this->page->assets(MediaType::HERO));
    }

    /** @test */
    public function it_can_add_a_new_image_with_a_random_key()
    {
        $response = $this->newImageRequest([
            'nl' => [
                99 => $this->dummySlimImagePayload('image.png', 'image/png', 150, 150),
            ],
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertCount(1, $this->page->assets(MediaType::HERO));
    }

    /** @test */
    public function a_module_can_add_a_new_image()
    {
        app(Register::class)->register(ImageFieldModuleManager::class, MediaModule::class);
        $module = MediaModule::create(['slug' => 'fake-module']);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['mediamodule', $module->id]), [
                'images' => [
                    MediaType::HERO => [
                        'nl' => [
                            $this->dummySlimImagePayload(),
                        ],
                    ]
                ]
            ]);

        $this->assertCount(1, $module->fresh()->assets(MediaType::HERO));
    }

    /** @test */
    public function an_existing_image_can_be_added()
    {
        $existing_asset = AssetUploader::upload(UploadedFile::fake()->image('image.png', 810, 810));

        $response = $this->newImageRequest([
            'nl' => [
                $existing_asset->id,
            ],
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertCount(1, $this->page->fresh()->assets(MediaType::HERO, 'nl'));

        $this->assertEquals($existing_asset->url(), $this->page->fresh()->asset(MediaType::HERO, 'nl')->url());
    }

    /** @test */
    public function it_can_upload_an_image_via_redactor_wysiwyg()
    {
        $response = $this->asAdmin()->post(route('chief.back.managers.media.upload', ['singles', $this->page->id]), [
            'files'   => [
                [
                    'data' => $this->dummyBase64Payload(),
                    'filename' => 'image.png',
                ]
            ],
            'locale' => 'nl',
        ]);

        $assets = $this->page->assets(MediaType::CONTENT, 'nl');
        $this->assertCount(1, $assets);

        $response->assertStatus(201)
            ->assertJson([
                "file-" . $assets->first()->id => [
                    "url" => $this->page->asset(MediaType::CONTENT)->url(),
                    "id"  => $assets->first()->id,
                ],
            ]);
    }

    /** @test */
    public function adding_same_existing_image_twice_will_only_add_it_once()
    {
        $existing_asset = AssetUploader::upload(UploadedFile::fake()->image('image.png', 810, 810));

        $response = $this->newImageRequest([
            'nl' => [
                $existing_asset->id, $existing_asset->id,
            ],
        ]);

        $response->assertSessionHasErrors();
        $this->assertStringContainsString('Een van de fotos die je uploadde bestond al.', session()->get('errors')->first());

        $this->assertCount(1, $this->page->fresh()->assets(MediaType::HERO));

        $this->assertEquals($existing_asset->url(), $this->page->fresh()->asset(MediaType::HERO, 'nl')->url());
    }

    /** @test */
    public function it_can_upload_translatable_images()
    {
        $this->newImageRequest([
            'nl' => [
                $this->dummySlimImagePayload('tt-favicon-nl.png', 'image/png', 800, 800),
            ],
            'en' => [
                $this->dummySlimImagePayload('tt-favicon-en.png', 'image/png', 800, 800),
            ]
        ]);

        $this->assertEquals('tt-favicon-nl.png', $this->page->asset(MediaType::HERO, 'nl')->filename());
        $this->assertEquals('tt-favicon-en.png', $this->page->asset(MediaType::HERO, 'en')->filename());
    }

    private function newImageRequest($payload): TestResponse
    {
        return $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams([
                'images' => [
                    MediaType::HERO => $payload,
                ],
            ]));
    }
}
