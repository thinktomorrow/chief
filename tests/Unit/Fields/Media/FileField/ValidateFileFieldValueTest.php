<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields\Media\FileField;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;

class ValidateFileFieldValueTest extends ChiefTestCase
{
    use PageFormParams;
    use UploadsFile;

    private $page;
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = $this->setupAndCreateArticleWithRequiredFile();
        $this->manager = $this->manager($this->page);
    }

    /** @test */
    public function it_validates_a_required_file()
    {
        $response = $this->uploadFile('thumb_trans', [
            'nl' => [null],
            'en' => [],
        ]);

        $response->assertSessionHasErrors('files.thumb_trans.nl');

        $this->assertCount(0, $this->page->assets('thumb_trans'));
    }

    /** @test */
    public function it_passed_file_validation_when_there_are_already_images_for_model_present()
    {
        $response = $this->uploadFile('thumb_trans', [
            'nl' => [2 => 2], // indicates there is already an asset on this model attached.
            'en' => [],
        ]);

        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function it_can_validate_the_dimensions()
    {
        $response = $this->uploadFile('thumb_trans', [
            'nl' => [UploadedFile::fake()->image('image.png', '50', '50')],
            'en' => [],
        ]);

        $response->assertSessionHasErrors('files.thumb_trans.nl');
        $this->assertStringContainsString('thumb trans heeft niet de juiste afmetingen', session()->get('errors')->first('files.thumb_trans.nl'));

        $this->assertCount(0, $this->page->assets('thumb_trans'));
    }

    /** @test */
    public function it_can_validate_the_dimensions_on_a_replacing_image()
    {
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('original-image.png'), 'thumb_trans', 'nl');
        $existing_asset_nl = $this->page->assets('thumb_trans', 'nl')->first();

        $response = $this->uploadFile('thumb_trans', [
            'nl' => [
                $existing_asset_nl->id => UploadedFile::fake()->image('replacing-image.png', '50', '50'),
            ],
            'en' => [],
        ]);

        $response->assertSessionHasErrors('files.thumb_trans.nl');
        $this->assertStringContainsString('thumb trans'.' heeft niet de juiste afmetingen', session()->get('errors')->first('files.thumb_trans.nl'));

        $this->assertEquals('original-image.png', $this->page->asset('thumb_trans')->filename());
    }

    /** @test */
    public function it_can_validate_a_max_filesize()
    {
        $response = $this->uploadFile('thumb_trans', [
            'nl' => [UploadedFile::fake()->image('image.png', '1000', '800')],
            'en' => [],
        ]);

        $response->assertSessionHasErrors('files.thumb_trans.nl');
        $this->assertStringContainsString('thumb trans is te groot en dient kleiner te zijn dan', session()->get('errors')->first('files.thumb_trans.nl'));

        $this->assertCount(0, $this->page->assets('thumb_trans'));
    }

    /** @test */
    public function it_can_validate_a_min_filesize()
    {
        $response = $this->uploadFile('thumb_trans', [
            'nl' => [UploadedFile::fake()->image('image.png', '101', '101')],
            'en' => [],
        ]);

        $response->assertSessionHasErrors('files.thumb_trans.nl');
        $this->assertStringContainsString('thumb trans'.' is te klein en dient groter te zijn dan', session()->get('errors')->first('files.thumb_trans.nl'));

        $this->assertCount(0, $this->page->assets('thumb_trans'));
    }

    /** @test */
    public function it_can_validate_a_mimetype()
    {
        $response = $this->uploadFile('thumb_trans', [
            'nl' => [UploadedFile::fake()->image('image.jpg', '200', '200')],
            'en' => [],
        ]);

        $response->assertSessionHasErrors('files.thumb_trans.nl');
        $this->assertStringContainsString('thumb trans'.' is niet het juiste bestandstype', session()->get('errors')->first('files.thumb_trans.nl'));

        $this->assertCount(0, $this->page->assets('thumb_trans'));
    }
}
