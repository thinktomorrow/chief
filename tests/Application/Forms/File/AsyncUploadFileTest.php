<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Forms\File;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;

class AsyncUploadFileTest extends ChiefTestCase
{
    use UploadsFile;
    private ArticlePage $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setupAndCreateArticle([
            'title' => 'Originele titel',
        ]);
    }

    /** @test */
    public function it_can_async_upload_file()
    {
        $this->asAdmin()->post($this->manager($this->model)->route('asyncUploadFile', 'thumb', $this->model->id), [
            'file' => UploadedFile::fake()->image('tt-favicon.png'),
            'locale' => 'nl',
        ]);

        $this->assertEquals(1, Asset::count());
        $this->assertEquals('tt-favicon.png', Asset::first()->filename());
    }

    /** @test */
    public function an_async_uploaded_file_can_be_saved_on_a_model()
    {
        $this->asAdmin()->post($this->manager($this->model)->route('asyncUploadFile', 'thumb', $this->model->id), [
            'file' => UploadedFile::fake()->image('tt-favicon.png'),
            'locale' => 'nl',
        ]);

        $this->assertEquals(1, Asset::count());
        $this->assertEquals('tt-favicon.png', Asset::first()->filename());

        $response = $this->uploadFile('thumb', [
            'nl' => [
                'thumb' => Asset::first()->id,
            ],
        ]);

        $response->assertRedirect();

        $this->assertEquals('tt-favicon.png', $this->model->fresh()->asset('thumb')->filename());
    }

    /** @test */
    public function it_can_async_upload_slim_image()
    {
        $this->asAdmin()->post($this->manager($this->model)->route('asyncUploadSlimImage', 'thumb', $this->model->id), [
            'files' => [
                'thumb' => [
                    'nl' => [
                        'new_jibberish' => $this->dummySlimImagePayload('tt-favicon.png'),
                    ],
                ],
            ],
        ]);

        $this->assertEquals(1, Asset::count());
        $this->assertEquals('tt-favicon.png', Asset::first()->filename());
    }

    /** @test */
    public function it_can_async_upload_slim_image_for_nested_fragment()
    {
        $this->disableExceptionHandling();
        $fragment = $this->setupAndCreateHero($this->model);

        $this->asAdmin()->post($this->manager($fragment)->route('asyncUploadSlimImage', 'thumb', $fragment->fragmentModel()->id), [
            'files' => [
                'thumb' => [
                    'nl' => [
                        'new_jibberish' => $this->dummySlimImagePayload('tt-favicon.png'),
                    ],
                ],
            ],
        ]);

        $this->assertEquals(1, Asset::count());
        $this->assertEquals('tt-favicon.png', Asset::first()->filename());
    }
}
