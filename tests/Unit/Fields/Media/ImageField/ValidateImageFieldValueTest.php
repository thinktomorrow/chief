<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields\Media\ImageField;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;

class ValidateImageFieldValueTest extends ChiefTestCase
{
    use PageFormParams;
    use UploadsFile;

    private $page;
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = $this->setupAndCreateArticleWithRequiredImage();
        $this->manager = $this->manager($this->page);
    }

    /** @test */
    public function it_validates_a_required_file()
    {
        $response = $this->uploadImage('thumb_image_trans', [
            'nl' => [null],
            'en' => [],
        ]);

        $response->assertSessionHasErrors('images.thumb_image_trans.nl');

        $this->assertCount(0, $this->page->assets('thumb_image_trans'));
    }

    /** @test */
    public function it_passed_file_validation_when_there_are_already_images_for_model_present()
    {
        $response = $this->uploadImage('thumb_image_trans', [
            'nl' => [2 => 2], // indicates there is already an asset on this model attached.
            'en' => [],
        ]);

        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function it_can_validate_the_dimensions()
    {
        $response = $this->uploadImage('thumb_image_trans', [
            'nl' => [$this->dummySlimImagePayload('image.png', 'image/png', 50, 50)],
            'en' => [],
        ]);

        $response->assertSessionHasErrors('images.thumb_image_trans.nl');
        $this->assertStringContainsString('thumb_image_trans heeft niet de juiste afmetingen', session()->get('errors')->first('images.thumb_image_trans.nl'));

        $this->assertCount(0, $this->page->assets('thumb_image_trans'));
    }

    public function it_can_validate_the_dimensions_on_a_replacing_image()
    {
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('original-image.png'), 'thumb_image_trans', 'nl');
        $existing_asset_nl = $this->page->assets('thumb_image_trans', 'nl')->first();

        $response = $this->uploadImage('thumb_image_trans', [
            'nl' => [
                $existing_asset_nl->id => $this->dummySlimImagePayload('replacing-image.png', 'image/png', 50, 50),
            ],
            'en' => [],
        ]);

        $response->assertSessionHasErrors('images.thumb_image_trans.nl');
        $this->assertStringContainsString('thumb_image_trans heeft niet de juiste afmetingen', session()->get('errors')->first('images.thumb_image_trans.nl'));

        $this->assertEquals('original-image.png', $this->page->asset('thumb_image_trans')->filename());
    }

    /** @test */
    public function it_can_validate_a_max_filesize()
    {
        $response = $this->uploadImage('thumb_image_trans', [
            'nl' => [
                $this->dummyLargeSlimImagePayload('image.png', 'image/png', 1000, 800),
            ],
            'en' => [],
        ]);

        $response->assertSessionHasErrors('images.thumb_image_trans.nl');
        $this->assertStringContainsString('thumb_image_trans is te groot en dient kleiner te zijn dan', session()->get('errors')->first('images.thumb_image_trans.nl'));

        $this->assertCount(0, $this->page->assets('thumb_image_trans'));
    }

    /** @test */
    public function it_can_validate_a_min_filesize()
    {
        $response = $this->uploadImage('thumb_image_trans', [
            'nl' => [
                $this->dummySmallSlimImagePayload('image.png', 'image/png', 100, 100, 900),
            ],
            'en' => [],
        ]);

        $response->assertSessionHasErrors('images.thumb_image_trans.nl');
        $this->assertStringContainsString('thumb_image_trans is te klein en dient groter te zijn dan', session()->get('errors')->first('images.thumb_image_trans.nl'));

        $this->assertCount(0, $this->page->assets('thumb_image_trans'));
    }

    /** @test */
    public function it_can_validate_a_mimetype()
    {
        $response = $this->uploadImage('thumb_image_trans', [
            'nl' => [
                $this->dummySlimImagePayload('image.jpg', 'image/jpg', 150, 150),
            ],
            'en' => [],
        ]);

        $response->assertSessionHasErrors('images.thumb_image_trans.nl');
        $this->assertStringContainsString('thumb_image_trans is niet het juiste bestandstype', session()->get('errors')->first('images.thumb_image_trans.nl'));

        $this->assertCount(0, $this->page->assets('thumb_image_trans'));
    }
}
