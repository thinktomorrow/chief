<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class UpdateFragmentTest extends ChiefTestCase
{
    private $owner;
    private $fragmentManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->setupAndCreateSnippet($this->owner);

        $this->fragmentManager = app(Registry::class)->manager(SnippetStub::managedModelKey());
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
        $model = app(FragmentRepository::class)->getByOwner($this->owner)->first();

        $response = $this->asAdmin()->put($this->fragmentManager->route('fragment-update', $model), [
            'custom' => 'custom-value',
            'files' => [
                'thumb' => [
                    'nl' => [
                        UploadedFile::fake()->image('tt-favicon.png'),
                    ],
                ],
            ],
        ]);

        $response->assertStatus(200);

        $snippet = app(FragmentRepository::class)->getByOwner($this->owner)->first();
        $this->assertEquals('tt-favicon.png', $snippet->fragmentModel()->asset('thumb')->filename());
    }
}
