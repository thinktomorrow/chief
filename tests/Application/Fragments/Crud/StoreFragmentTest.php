<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments\Crud;

use function app;
use function chiefRegister;
use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class StoreFragmentTest extends ChiefTestCase
{
    private $owner;
    private $fragmentManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();

        chiefRegister()->fragment(SnippetStub::class);
        $this->fragmentManager = $this->manager(SnippetStub::class);
    }

    /** @test */
    public function it_can_store_a_fragment()
    {
        $this->asAdmin()->post($this->fragmentManager->route('fragment-store', $this->owner), [
            'title' => 'new-title',
            'trans' => [
                'nl' => ['title_trans' => 'title_trans nl value'],
                'en' => ['title_trans' => 'title_trans en value'],
            ],

        ]);

        $this->assertEquals(1, FragmentModel::count());

        $snippet = app(FragmentRepository::class)->getByOwner($this->owner)->first();
        $this->assertInstanceOf(SnippetStub::class, $snippet);
        $this->assertEquals('new-title', $snippet->fragmentModel()->title);

        app()->setLocale('nl');
        $this->assertEquals('title_trans nl value', $snippet->fragmentModel()->title_trans);

        app()->setLocale('en');
        $this->assertEquals('title_trans en value', $snippet->fragmentModel()->title_trans);
    }

    /** @test */
    public function it_can_store_fragment_with_specific_order()
    {
        $snippet1 = $this->setupAndCreateSnippet($this->owner, 1, false);
        $snippet2 = $this->setupAndCreateSnippet($this->owner, 2, false);
        $snippet3 = $this->setupAndCreateSnippet($this->owner, 3, false);

        $this->asAdmin()->post($this->fragmentManager->route('fragment-store', $this->owner), [
            'title' => 'new-title',
            'order' => 1,
        ]);

        $fragments = app(FragmentRepository::class)->getByOwner($this->owner);
        $this->assertCount(4, $fragments);

        $this->assertEquals($snippet1->modelReference(), $fragments[0]->modelReference());
        $this->assertEquals(ModelReference::make(SnippetStub::class, 0), $fragments[1]->modelReference());
        $this->assertEquals($snippet2->modelReference(), $fragments[2]->modelReference());
        $this->assertEquals($snippet3->modelReference(), $fragments[3]->modelReference());

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

        $snippet = $this->firstFragment($this->owner);
        $this->assertEquals('tt-favicon.png', $snippet->fragmentModel()->asset('thumb')->filename());
    }
}
