<?php

namespace Thinktomorrow\Chief\Tests\Feature\Media\ImageField;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Management\Register;
use Illuminate\Foundation\Testing\TestResponse;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Tests\Feature\Media\Fakes\ImageFieldManagerWithValidation;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;

class ValidateImageFieldValueTest extends TestCase
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
    public function it_validates_a_required_file()
    {
        $response = $this->newImageRequest([null]);

        $response->assertSessionHasErrors('images.images-hero.nl');

        $this->assertCount(0, $this->page->assets(MediaType::HERO));
    }

    /** @test */
    public function it_passed_file_validation_when_there_are_already_images_for_model_present()
    {
        $response = $this->newImageRequest([
            2 => 2, // indicates there is already an asset on this model attached.
        ]);

        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function it_can_validate_the_dimensions()
    {
        $response = $this->newImageRequest([
            $this->dummySlimImagePayload('image.png', 'image/png', 50, 50)
        ]);

        $response->assertSessionHasErrors('images.images-hero.nl');
        $this->assertStringContainsString('De images-hero heeft niet de juiste afmetingen', session()->get('errors')->first('images.images-hero.nl'));

        $this->assertCount(0, $this->page->assets(MediaType::HERO));
    }

    public function it_can_validate_the_dimensions_on_a_replacing_image()
    {
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('original-image.png'), static::FILEFIELD_KEY, 'nl');
        $existing_asset_nl = $this->page->assets(static::FILEFIELD_KEY, 'nl')->first();

        $response = $this->newImageRequest([
            $existing_asset_nl->id => $this->dummySlimImagePayload('replacing-image.png', 'image/png', 50, 50)
        ]);

        $response->assertSessionHasErrors('images.images-hero.nl');
        $this->assertStringContainsString('De images-hero heeft niet de juiste afmetingen', session()->get('errors')->first('images.images-hero.nl'));

        $this->assertEquals('original-image.png', $this->page->asset(MediaType::HERO)->filename());
    }

    /** @test */
    public function it_can_validate_a_max_filesize()
    {
        $response = $this->newImageRequest([
            $this->dummyLargeSlimImagePayload('image.png', 'image/png', 1000, 800)
        ]);

        $response->assertSessionHasErrors('images.images-hero.nl');
        $this->assertStringContainsString('De images-hero is te groot en dient kleiner te zijn dan', session()->get('errors')->first('images.images-hero.nl'));

        $this->assertCount(0, $this->page->assets(MediaType::HERO));
    }

    /** @test */
    public function it_can_validate_a_min_filesize()
    {
        $response = $this->newImageRequest([
            $this->dummySmallSlimImagePayload('image.png', 'image/png', 100, 100, 900)
        ]);

        $response->assertSessionHasErrors('images.images-hero.nl');
        $this->assertStringContainsString('De images-hero is te klein en dient groter te zijn dan', session()->get('errors')->first('images.images-hero.nl'));

        $this->assertCount(0, $this->page->assets(MediaType::HERO));
    }

    /** @test */
    public function it_can_validate_a_mimetype()
    {
        $response = $this->newImageRequest([
            $this->dummySlimImagePayload('image.jpg', 'image/jpg', 150, 150)
        ]);

        $response->assertSessionHasErrors('images.images-hero.nl');
        $this->assertStringContainsString('De images-hero is niet het juiste bestandstype', session()->get('errors')->first('images.images-hero.nl'));

        $this->assertCount(0, $this->page->assets(MediaType::HERO));
    }

    private function newImageRequest($payload = []): TestResponse
    {
        return $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams([
                'images' => [
                    MediaType::HERO => [
                        'nl' => $payload,
                    ]
                ]
            ]));
    }

//    /** @test */
//    public function it_can_add_image_via_wysiwyg_editor()
//    {
//        $this->setUpDefaultAuthorization();
//
//        $article = ArticlePageFake::create();
//
//        $response = $this->asAdmin()->post(route('chief.back.managers.media.upload', ['singles', $article->id]), [
//            'file' => [
//                UploadedFile::fake()->image('image.png')
//            ],
//            'locale' => 'nl'
//        ]);
//
//        $assets = $article->assets(MediaType::CONTENT, 'nl');
//        $this->assertCount(1, $assets);
//
//        $response->assertStatus(201)
//            ->assertJson([
//                "file-".$assets->first()->id => [
//                    "url" => $article->asset(MediaType::CONTENT)->url(),
//                    "id" => $assets->first()->id,
//                ]
//            ]);
//    }
}
