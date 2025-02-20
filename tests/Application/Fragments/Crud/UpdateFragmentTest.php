<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments\Crud;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

use function app;

class UpdateFragmentTest extends ChiefTestCase
{
    private $owner;

    private $fragmentManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->setupAndCreateSnippet($this->owner);

        $this->fragmentManager = $this->manager(SnippetStub::class);
    }

    /** @test */
    public function it_can_update_a_model()
    {
        $model = app(FragmentRepository::class)->getByOwner($this->owner)->first();

        $this->asAdmin()->put($this->fragmentManager->route('fragment-update', $model), [
            'title' => 'new-title',
            'trans' => [
                'nl' => ['title_trans' => 'title_trans nl value'],
                'en' => ['title_trans' => 'title_trans en value'],
            ],
        ]);

        $snippet = app(FragmentRepository::class)->getByOwner($this->owner)->first();
        $this->assertInstanceOf(SnippetStub::class, $snippet);
        $this->assertEquals('new-title', $snippet->fragmentModel()->title);

        app()->setLocale('nl');
        $this->assertEquals('title_trans nl value', $snippet->fragmentModel()->title_trans);

        app()->setLocale('en');
        $this->assertEquals('title_trans en value', $snippet->fragmentModel()->title_trans);
    }

    /** @test */
    public function it_can_upload_a_file_field()
    {
        UploadedFile::fake()->image('tt-favicon.png')->storeAs('test', 'image-temp-name.png');

        $model = app(FragmentRepository::class)->getByOwner($this->owner)->first();

        $response = $this->asAdmin()->put($this->fragmentManager->route('fragment-update', $model), [
            'custom' => 'custom-value',
            'files' => [
                'thumb' => [
                    'nl' => [
                        'uploads' => [
                            [
                                'id' => 'xxx',
                                'path' => Storage::path('test/image-temp-name.png'),
                                'originalName' => 'tt-favicon.png',
                                'mimeType' => 'image/png',
                                'fieldValues' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $response->assertStatus(200);

        $snippet = app(FragmentRepository::class)->getByOwner($this->owner)->first();
        $this->assertEquals('tt-favicon.png', $snippet->fragmentModel()->asset('thumb')->filename());
    }
}
