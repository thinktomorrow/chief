<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentModel;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class CreateFragmentTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
    }

    public function test_it_can_create_fragment_model()
    {
        $fragmentId = app(CreateFragment::class)->handle(
            SnippetStub::resourceKey(),
            ['title' => 'bar'],
            []
        );

        $model = FragmentModel::find($fragmentId);

        $this->assertEquals(SnippetStub::resourceKey(), $model->key);
        $this->assertEquals('bar', $model->title);
    }

    public function test_it_can_create_fragment_model_with_localized_fields()
    {
        $fragmentId = app(CreateFragment::class)->handle(
            SnippetStub::resourceKey(),
            [
                'title' => 'bar',
                'trans' => [
                    'nl' => ['title_trans' => 'nl titel'],
                    'en' => ['title_trans' => 'en title'],
                ],
            ],
            []
        );

        $model = FragmentModel::find($fragmentId);

        $this->assertEquals(SnippetStub::resourceKey(), $model->key);

        app()->setLocale('nl');
        $this->assertEquals('nl titel', $model->title_trans);

        app()->setLocale('en');
        $this->assertEquals('en title', $model->title_trans);
    }
}
