<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Controllers;

use function app;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentRepository;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class UpdateFragmentTest extends ChiefTestCase
{
    private $owner;
    private SnippetStub $fragment;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->fragment = $this->setupAndCreateSnippet($this->owner);
    }

    public function test_it_can_update_a_fragment()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');

        $this->asAdmin()->put(route('chief::fragments.update', [$context->id, $this->fragment->getFragmentId()]), [
            'title' => 'new-title',
        ])->assertStatus(204);

        $snippet = app(FragmentRepository::class)->find($this->fragment->getFragmentId());

        $this->assertInstanceOf(SnippetStub::class, $snippet);
        $this->assertEquals('new-title', $snippet->fragmentModel()->title);
    }

    public function test_it_can_update_a_fragment_locale_values()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');

        $this->asAdmin()->put(route('chief::fragments.update', [$context->id, $this->fragment->getFragmentId()]), [
            'title' => 'new-title',
            'trans' => [
                'nl' => ['title_trans' => 'title_trans nl value'],
                'en' => ['title_trans' => 'title_trans en value'],
            ],
        ])->assertStatus(204);

        $snippet = app(FragmentRepository::class)->find($this->fragment->getFragmentId());

        app()->setLocale('nl');
        $this->assertEquals('title_trans nl value', $snippet->fragmentModel()->title_trans);

        app()->setLocale('en');
        $this->assertEquals('title_trans en value', $snippet->fragmentModel()->title_trans);
    }

    public function test_it_can_update_a_nested_fragment()
    {
        $fragmentId = app(CreateFragment::class)->handle(SnippetStub::resourceKey(), ['title' => 'owning fragment'], []);
        $context = ContextModel::create(['owner_type' => FragmentModel::resourceKey(), 'owner_id' => $fragmentId, 'locale' => 'nl']);

        $this->asAdmin()->put(route('chief::fragments.nested.update', [$context->id, $this->fragment->getFragmentId()]), [
            'title' => 'new-title',
        ])->assertStatus(204);

        $snippet = app(FragmentRepository::class)->find($this->fragment->getFragmentId());

        $this->assertInstanceOf(SnippetStub::class, $snippet);
        $this->assertEquals('new-title', $snippet->fragmentModel()->title);
    }

    public function test_it_can_upload_a_file_field()
    {
        UploadedFile::fake()->image('tt-favicon.png')->storeAs('test', 'image-temp-name.png');

        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');

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

        $snippet = app(FragmentRepository::class)->find($this->fragment->getFragmentId());

        $this->assertEquals('tt-favicon.png', $snippet->fragmentModel()->asset('thumb')->filename());
    }
}
