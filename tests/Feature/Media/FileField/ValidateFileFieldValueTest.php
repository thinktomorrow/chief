<?php

namespace Thinktomorrow\Chief\Tests\Feature\Media\FileField;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Illuminate\Foundation\Testing\TestResponse;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Tests\Feature\Media\Fakes\FileFieldManagerWithValidation;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;

class ValidateFileFieldValueTest extends TestCase
{
    const FILEFIELD_KEY = 'fake-file';

    use PageFormParams;

    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(FileFieldManagerWithValidation::class, Single::class);
        $this->page = Single::create();
    }

    /** @test */
    public function it_validates_a_required_file()
    {
        $response = $this->newFileRequest([null]);

        $response->assertSessionHasErrors('files.'.static::FILEFIELD_KEY.'.nl');

        $this->assertCount(0, $this->page->assets(static::FILEFIELD_KEY));
    }

    /** @test */
    public function it_passed_file_validation_when_there_are_already_images_for_model_present()
    {
        $response = $this->newFileRequest([], [
            2 => null, // indicates there is already an asset on this model attached.
        ]);

        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function it_can_validate_the_dimensions()
    {
        $response = $this->newFileRequest([UploadedFile::fake()->image('image.png','50','50')]);

        $response->assertSessionHasErrors('files.'.static::FILEFIELD_KEY.'.nl');
        $this->assertStringContainsString('De '.static::FILEFIELD_KEY.' heeft niet de juiste afmetingen', session()->get('errors')->first('files.'.static::FILEFIELD_KEY.'.nl'));

        $this->assertCount(0, $this->page->assets(static::FILEFIELD_KEY));
    }

    /** @test */
    public function it_can_validate_the_dimensions_on_a_replacing_image()
    {
        app(AddAsset::class)->add($this->page, UploadedFile::fake()->image('original-image.png'), static::FILEFIELD_KEY, 'nl');
        $existing_asset_nl = $this->page->assets(static::FILEFIELD_KEY, 'nl')->first();

        $response = $this->newFileRequest([],[
            $existing_asset_nl->id => UploadedFile::fake()->image('replacing-image.png','50','50')
        ]);

        $response->assertSessionHasErrors('files.'.static::FILEFIELD_KEY.'.nl');
        $this->assertStringContainsString('De '.static::FILEFIELD_KEY.' heeft niet de juiste afmetingen', session()->get('errors')->first('files.'.static::FILEFIELD_KEY.'.nl'));

        $this->assertEquals('original-image.png', $this->page->asset(static::FILEFIELD_KEY)->filename());
    }

    /** @test */
    public function it_can_validate_a_max_filesize()
    {
        $response = $this->newFileRequest([UploadedFile::fake()->image('image.png','1000','800')]);

        $response->assertSessionHasErrors('files.'.static::FILEFIELD_KEY.'.nl');
        $this->assertStringContainsString('De '.static::FILEFIELD_KEY.' is te groot en dient kleiner te zijn dan', session()->get('errors')->first('files.'.static::FILEFIELD_KEY.'.nl'));

        $this->assertCount(0, $this->page->assets(static::FILEFIELD_KEY));
    }

    /** @test */
    public function it_can_validate_a_min_filesize()
    {
        $response = $this->newFileRequest([UploadedFile::fake()->image('image.png','101','101')]);

        $response->assertSessionHasErrors('files.'.static::FILEFIELD_KEY.'.nl');
        $this->assertStringContainsString('De '.static::FILEFIELD_KEY.' is te klein en dient groter te zijn dan', session()->get('errors')->first('files.'.static::FILEFIELD_KEY.'.nl'));

        $this->assertCount(0, $this->page->assets(static::FILEFIELD_KEY));
    }

    /** @test */
    public function it_can_validate_a_mimetype()
    {
        $response = $this->newFileRequest([UploadedFile::fake()->image('image.jpg','200','200')]);

        $response->assertSessionHasErrors('files.'.static::FILEFIELD_KEY.'.nl');
        $this->assertStringContainsString('De '.static::FILEFIELD_KEY.' is niet het juiste bestandstype', session()->get('errors')->first('files.'.static::FILEFIELD_KEY.'.nl'));

        $this->assertCount(0, $this->page->assets(static::FILEFIELD_KEY));
    }

    /** @test */
    public function it_can_apply_validations_on_wysiwyg_uploads()
    {

    }

    private function newFileRequest($new = [], $replace = []): TestResponse
    {
        return $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $this->page->id]), $this->validUpdatePageParams([
                'files' => [
                    static::FILEFIELD_KEY => [
                        'nl' => [
                            'new' => $new,
                            'replace' => $replace,
                        ],
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
