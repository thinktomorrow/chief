<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Forms\File;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\UploadsFile;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

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

//trans[nl][intro]: edit
//trans[fr][intro]:
//trans[en][intro]:
//files[test][nl][new_SURQOk]:
//filesOrder[nl][test]:
//files[test][fr][12]: 12
//filesOrder[fr][test]:
//filesOrder[en][test]:
//files[thumb][nl][4]: 4
//filesOrder[nl][thumb]: 4
//filesOrder[fr][thumb]:
//filesOrder[en][thumb]:

    /** @test */
    public function it_can_async_upload_slim_image()
    {
        $this->disableExceptionHandling();
        $this->asAdmin()->post($this->manager($this->model)->route('asyncUploadSlimImage', 'thumb', $this->model->id), [
            'files' => [
                'thumb' => [
                    'nl' => [
                        'new_jibberish' => $this->dummySlimImagePayload('tt-favicon.png')
                    ],
                ],
            ],
        ]);

        $this->assertEquals(1, Asset::count());
        $this->assertEquals('tt-favicon.png', Asset::first()->filename());
    }
}
