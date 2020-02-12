<?php

namespace Thinktomorrow\Chief\Tests\Feature\MediaOld;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Media\Fakes\FileFieldManagerWithValidation;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;

class FileFieldTest extends TestCase
{
    use PageFormParams;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('app.fallback_locale', 'nl');

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(FileFieldManagerWithValidation::class, Single::class);
    }

    /** @test */
    public function it_can_upload_a_file()
    {
        $this->disableExceptionHandling();
        $page = Single::create();

        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'nl' => [
                            'new' => [
                                UploadedFile::fake()->image('image.png', 200, 200),
                                $this->dummyUploadedFile('document.txt')
                            ],
                            'replace' => [],
                        ],
                    ]
                ]
            ]));

        $response->assertSessionHasNoErrors();

        $this->assertCount(2, $page->assets(MediaType::HERO));
    }

    /** @test */
    function it_validates_a_required_file()
    {
        $page = Single::create();

        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'nl' => [
                            'new' => [],
                            'replace' => []
                        ],
                    ]
                ]
            ]));

        $response->assertSessionHasErrors('files.images-hero.nl');

        $this->assertCount(0, $page->assets(MediaType::HERO));
    }

    /** @test */
    function it_can_validate_the_dimensions()
    {
        $page = Single::create();

        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'nl' => [
                            'new' => [
                                UploadedFile::fake()->image('image.png', 50, 50),
                                $this->dummyUploadedFile('document.txt')
                            ],
                            'replace' => [],
                        ],
                    ]
                ]
            ]));

        $response->assertSessionHasErrors('files.images-hero.nl');
        $this->assertStringContainsString('De images-hero heeft niet de juiste afmetingen', session()->get('errors')->first('files.images-hero.nl'));

        $this->assertCount(0, $page->assets(MediaType::HERO));
    }

    /** @test */
    function it_can_validate_a_max_filesize()
    {
        $page = Single::create();

        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'nl' => [
                            'new' => [
                                UploadedFile::fake()->image('image.png', 1000, 800),
                            ],
                            'replace' => [],
                        ],
                    ]
                ]
            ]));

        $response->assertSessionHasErrors('files.images-hero.nl');
        $this->assertStringContainsString('De images-hero is te groot en dient kleiner te zijn dan', session()->get('errors')->first('files.images-hero.nl'));

        $this->assertCount(0, $page->assets(MediaType::HERO));
    }

    /** @test */
    function it_can_validate_a_min_filesize()
    {
        $page = Single::create();

        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'nl' => [
                            'new' => [
                                UploadedFile::fake()->image('image.png', 501, 501),
                            ],
                            'replace' => [],
                        ],
                    ]
                ]
            ]));

        $response->assertSessionHasErrors('files.images-hero.nl');
        $this->assertStringContainsString('De images-hero is te klein en dient groter te zijn dan', session()->get('errors')->first('files.images-hero.nl'));

        $this->assertCount(0, $page->assets(MediaType::HERO));
    }

    /** @test */
    function it_can_validate_a_mimetype()
    {
        $page = Single::create();

        $response = $this->asAdmin()
            ->put(route('chief.back.managers.update', ['singles', $page->id]), $this->validUpdatePageParams([
                'files' => [
                    MediaType::HERO => [
                        'nl' => [
                            'new' => [
                                UploadedFile::fake()->image('image.jpg', 800, 800),
                            ],
                            'replace' => [],
                        ],
                    ]
                ]
            ]));

        $response->assertSessionHasErrors('files.images-hero.nl');
        $this->assertStringContainsString('De images-hero is niet het juiste bestandstype', session()->get('errors')->first('files.images-hero.nl'));

        $this->assertCount(0, $page->assets(MediaType::HERO));
    }

    /** @test */
    function it_can_apply_validations_on_wysiwyg_uploads()
    {

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
