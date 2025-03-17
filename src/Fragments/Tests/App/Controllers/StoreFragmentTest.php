<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

use function app;
use function chiefRegister;

class StoreFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_it_can_store_a_root_fragment()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);

        $this->assertEquals(0, FragmentModel::count());

        $this->asAdmin()->post(route('chief::fragments.store', [$context->id, SnippetStub::resourceKey()]), [
            'title' => 'new-title',
            'order' => 2,
        ])->assertStatus(201);

        $this->assertEquals(1, FragmentModel::count());

        $snippet = FragmentTestHelpers::firstFragment($context->id);
        $this->assertInstanceOf(SnippetStub::class, $snippet);
        $this->assertEquals('new-title', $snippet->getFragmentModel()->title);
    }

    public function test_it_can_store_a_fragment()
    {
        $fragment = FragmentTestHelpers::createFragment(
            SnippetStub::class,
            ['title' => 'owning fragment']
        );

        $context = FragmentTestHelpers::createContext($fragment);
        $this->asAdmin()->post(route('chief::fragments.nested.store', [$context->id, SnippetStub::resourceKey()]), [
            'title' => 'new-title',
            'order' => 2,

        ])->assertStatus(201);

        $this->assertEquals(2, FragmentModel::count());

        $snippet = FragmentTestHelpers::firstFragment($context->id);
        $this->assertInstanceOf(SnippetStub::class, $snippet);
        $this->assertEquals('new-title', $snippet->getFragmentModel()->title);
    }

    public function test_it_can_store_a_fragment_with_localized_fields()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);

        $this->asAdmin()->post(route('chief::fragments.store', [$context->id, SnippetStub::resourceKey()]), [
            'title' => 'new-title',
            'trans' => [
                'nl' => ['title_trans' => 'title_trans nl value'],
                'en' => ['title_trans' => 'title_trans en value'],
            ],
            'order' => 2,

        ])->assertStatus(201);

        $snippet = FragmentTestHelpers::firstFragment($context->id);

        app()->setLocale('nl');
        $this->assertEquals('title_trans nl value', $snippet->getFragmentModel()->title_trans);

        app()->setLocale('en');
        $this->assertEquals('title_trans en value', $snippet->getFragmentModel()->title_trans);
    }

    public function test_it_can_upload_a_file_field()
    {
        $this->disableExceptionHandling();
        UploadedFile::fake()->image('tt-favicon.png')->storeAs('test', 'image-temp-name.png');

        $context = FragmentTestHelpers::createContext($this->owner);

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

        $snippet = FragmentTestHelpers::firstFragment($context->id);

        $this->assertEquals('tt-favicon.png', $snippet->fragmentModel()->asset('thumb')->filename());
    }
}
