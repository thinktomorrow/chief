<?php

namespace Thinktomorrow\Chief\Tests\Unit\Managers\Assistants\FragmentAssistant;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Managers\Presets\FragmentManager;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class StoreFragmentActionTest extends ChiefTestCase
{
    private $owner;
    private $fragmentManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();

        Quote::migrateUp();
        chiefRegister()->model(Quote::class, FragmentManager::class);

        $this->fragmentManager = app(Registry::class)->manager(Quote::managedModelKey());
    }

    /** @test */
    public function it_can_store_a_fragment()
    {
        $this->asAdmin()->post($this->fragmentManager->route('fragment-store', $this->owner), [
            'title' => 'new-title',
            'custom' => 'custom-value',
            'trans' => [
                'nl' => ['title_trans' => 'title_trans nl value'],
                'en' => ['title_trans' => 'title_trans en value'],
            ],

        ]);

        $this->assertEquals(1, Quote::count());

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
        $response = $this->asAdmin()->post($this->fragmentManager->route('fragment-store', $this->owner), [
            'custom' => 'custom-value',
            'files' => [
                'thumb' => [
                    'nl' => [
                        UploadedFile::fake()->image('tt-favicon.png'),
                    ],
                ],
            ],
        ]);

        $response->assertStatus(201);

        $quote = app(FragmentRepository::class)->getByOwner($this->owner)->first();
        $this->assertEquals('tt-favicon.png', $quote->asset('thumb')->filename());
    }
}
