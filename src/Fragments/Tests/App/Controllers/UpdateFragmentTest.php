<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class UpdateFragmentTest extends ChiefTestCase
{
    private $owner;

    private Fragment $fragment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->fragment = FragmentTestHelpers::createFragment(SnippetStub::class);
    }

    public function test_it_can_update_a_fragment()
    {
        $context = FragmentTestHelpers::createContext($this->owner);

        $this->asAdmin()->put(route('chief::fragments.update', [$context->id, $this->fragment->getFragmentId()]), [
            'title' => 'new-title',
        ])->assertStatus(204);

        $snippet = FragmentTestHelpers::findFragment($this->fragment->getFragmentId());

        $this->assertInstanceOf(SnippetStub::class, $snippet);
        $this->assertEquals('new-title', $snippet->getFragmentModel()->title);
    }

    public function test_it_can_update_a_fragment_locale_values()
    {
        $context = FragmentTestHelpers::createContext($this->owner);

        $this->asAdmin()->put(route('chief::fragments.update', [$context->id, $this->fragment->getFragmentId()]), [
            'title' => 'new-title',
            'trans' => [
                'nl' => ['title_trans' => 'title_trans nl value'],
                'en' => ['title_trans' => 'title_trans en value'],
            ],
        ])->assertStatus(204);

        $snippet = FragmentTestHelpers::findFragment($this->fragment->getFragmentId());

        app()->setLocale('nl');
        $this->assertEquals('title_trans nl value', $snippet->getFragmentModel()->title_trans);

        app()->setLocale('en');
        $this->assertEquals('title_trans en value', $snippet->getFragmentModel()->title_trans);
    }

    public function test_it_can_update_a_nested_fragment()
    {
        $fragment = FragmentTestHelpers::createFragment(SnippetStub::class);
        $context = FragmentTestHelpers::createContext($fragment);

        $this->asAdmin()->put(route('chief::fragments.nested.update', [$context->id, $this->fragment->getFragmentId()]), [
            'title' => 'new-title',
        ])->assertStatus(204);

        $snippet = FragmentTestHelpers::findFragment($this->fragment->getFragmentId());

        $this->assertInstanceOf(SnippetStub::class, $snippet);
        $this->assertEquals('new-title', $snippet->getFragmentModel()->title);
    }

    public function test_it_can_upload_a_file_field()
    {
        UploadedFile::fake()->image('tt-favicon.png')->storeAs('test', 'image-temp-name.png');

        $context = FragmentTestHelpers::createContext($this->owner);

        $this->asAdmin()->put(route('chief::fragments.update', [$context->id, $this->fragment->getFragmentId()]), [
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
        ])->assertStatus(204);

        $snippet = FragmentTestHelpers::findFragment($this->fragment->getFragmentId());

        $this->assertEquals('tt-favicon.png', $snippet->getFragmentModel()->asset('thumb')->filename());
    }
}
