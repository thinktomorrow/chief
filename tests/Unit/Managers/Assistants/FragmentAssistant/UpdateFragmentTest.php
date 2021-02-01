<?php

namespace Thinktomorrow\Chief\Tests\Unit\Managers\Assistants\FragmentAssistant;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;

class UpdateFragmentTest extends ChiefTestCase
{
    private $owner;
    private $fragmentManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->setupAndCreateQuote($this->owner);

        $this->fragmentManager = app(Registry::class)->manager(Quote::managedModelKey());
    }

    /** @test */
    public function it_can_update_a_model()
    {
        $model = app(FragmentRepository::class)->getByOwner($this->owner)->first();

        $this->asAdmin()->put($this->fragmentManager->route('fragment-update', $model), [
            'title' => 'new-title',
            'custom' => 'custom-value',
            'trans' => [
                'nl' => ['title_trans' => 'title_trans nl value'],
                'en' => ['title_trans' => 'title_trans en value'],
            ],
        ]);

        $quote = app(FragmentRepository::class)->getByOwner($this->owner)->first();
        $this->assertInstanceOf(Quote::class, $quote);
        $this->assertEquals('new-title', $quote->title);
        $this->assertEquals('custom-value', $quote->custom);

        app()->setLocale('nl');
        $this->assertEquals('title_trans nl value', $quote->title_trans);

        app()->setLocale('en');
        $this->assertEquals('title_trans en value', $quote->title_trans);
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

        $quote = app(FragmentRepository::class)->getByOwner($this->owner)->first();
        $this->assertEquals('tt-favicon.png', $quote->asset('thumb')->filename());
    }
}
