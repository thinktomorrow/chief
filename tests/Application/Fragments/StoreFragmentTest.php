<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Managers\Presets\FragmentManager;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class StoreFragmentTest extends ChiefTestCase
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
    public function it_can_store_fragment_with_specific_order()
    {
        $this->disableExceptionHandling();
        $quote1 = $this->setupAndCreateQuote($this->owner, [], 1, false);
        $quote2 = $this->setupAndCreateQuote($this->owner, [], 2, false);
        $quote3 = $this->setupAndCreateQuote($this->owner, [], 3, false);

        $this->asAdmin()->post($this->fragmentManager->route('fragment-store', $this->owner), [
            'title' => 'new-title',
            'custom' => 'custom-value',
            'order' => 1,
        ]);

        $fragments = app(FragmentRepository::class)->getByOwner($this->owner);
        $this->assertCount(4, $fragments);

        $this->assertEquals($quote1->modelReference(), $fragments[0]->modelReference());
        $this->assertEquals(ModelReference::make(Quote::class, 4), $fragments[1]->modelReference());
        $this->assertEquals($quote2->modelReference(), $fragments[2]->modelReference());
        $this->assertEquals($quote3->modelReference(), $fragments[3]->modelReference());

        // Assert order is updated accordingly
        $this->assertEquals(0, $fragments[0]->fragmentModel()->pivot->order);
        $this->assertEquals(1, $fragments[1]->fragmentModel()->pivot->order);
        $this->assertEquals(2, $fragments[2]->fragmentModel()->pivot->order);
        $this->assertEquals(3, $fragments[3]->fragmentModel()->pivot->order);
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
