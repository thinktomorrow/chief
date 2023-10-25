<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Controllers;

use function app;
use function chiefRegister;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentRepository;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class StoreFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_it_can_store_a_fragment()
    {
        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);

        $this->asAdmin()->post(route('chief::fragments.store', [$context->id, SnippetStub::resourceKey()]), [
            'title' => 'new-title',
            'order' => 2,

        ])->assertStatus(201);

        $this->assertEquals(1, FragmentModel::count());

        $snippet = app(FragmentRepository::class)->getByOwner($this->owner, 'nl')->first();
        $this->assertInstanceOf(SnippetStub::class, $snippet);
        $this->assertEquals('new-title', $snippet->fragmentModel()->title);
    }

    public function test_it_can_store_a_fragment_with_localized_fields()
    {
        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);

        $this->asAdmin()->post(route('chief::fragments.store', [$context->id, SnippetStub::resourceKey()]), [
            'title' => 'new-title',
            'trans' => [
                'nl' => ['title_trans' => 'title_trans nl value'],
                'en' => ['title_trans' => 'title_trans en value'],
            ],
            'order' => 2,

        ])->assertStatus(201);

        $snippet = app(FragmentRepository::class)->getByOwner($this->owner, 'nl')->first();

        app()->setLocale('nl');
        $this->assertEquals('title_trans nl value', $snippet->fragmentModel()->title_trans);

        app()->setLocale('en');
        $this->assertEquals('title_trans en value', $snippet->fragmentModel()->title_trans);
    }

    public function test_it_can_store_a_nested_fragment()
    {
        $fragmentId = app(CreateFragment::class)->handle(
            SnippetStub::resourceKey(),
            ['title' => 'owning fragment'],
            []
        );

        $context = ContextModel::create(['owner_type' => FragmentModel::resourceKey(), 'owner_id' => $fragmentId, 'locale' => 'nl']);
        $this->asAdmin()->post(route('chief::fragments.nested.store', [$context->id, SnippetStub::resourceKey()]), [
            'title' => 'new-title',
            'order' => 2,

        ])->assertStatus(201);

        $this->assertEquals(2, FragmentModel::count());

        $snippet = app(FragmentRepository::class)->getByOwner(FragmentModel::find($fragmentId), 'nl')->first();
        $this->assertInstanceOf(SnippetStub::class, $snippet);
        $this->assertEquals('new-title', $snippet->fragmentModel()->title);
    }

    public function test_it_can_store_fragment_with_specific_order()
    {
        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);
        $snippet1 = $this->setupAndCreateSnippet($this->owner, 1, false);
        $snippet2 = $this->setupAndCreateSnippet($this->owner, 2, false);
        $snippet3 = $this->setupAndCreateSnippet($this->owner, 3, false);

        $response = $this->asAdmin()->post(route('chief::fragments.store', [$context->id, SnippetStub::resourceKey()]), [
            'title' => 'new-title',
            'order' => 1,
        ])->assertStatus(201);

        $insertedFragmentId = $response->getOriginalContent()['data']['fragmentmodel_id'];

        $fragments = app(FragmentRepository::class)->getByOwner($this->owner, 'nl');
        $this->assertCount(4, $fragments);

        $this->assertEquals($snippet1->modelReference(), $fragments[0]->modelReference());
        $this->assertEquals($insertedFragmentId, $fragments[1]->modelReference()->id());
        $this->assertEquals($snippet2->modelReference(), $fragments[2]->modelReference());
        $this->assertEquals($snippet3->modelReference(), $fragments[3]->modelReference());

        // Assert order is updated accordingly
        $this->assertEquals(0, $fragments[0]->fragmentModel()->pivot->order);
        $this->assertEquals(1, $fragments[1]->fragmentModel()->pivot->order);
        $this->assertEquals(2, $fragments[2]->fragmentModel()->pivot->order);
        $this->assertEquals(3, $fragments[3]->fragmentModel()->pivot->order);
    }

    public function test_it_can_upload_a_file_field()
    {
        UploadedFile::fake()->image('tt-favicon.png')->storeAs('test', 'image-temp-name.png');

        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);
        $this->asAdmin()->post(route('chief::fragments.store', [$context->id, SnippetStub::resourceKey()]), [
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
        ])->assertStatus(201);

        $snippet = $this->firstFragment($this->owner, 'nl');
        $this->assertEquals('tt-favicon.png', $snippet->fragmentModel()->asset('thumb')->filename());
    }
}
