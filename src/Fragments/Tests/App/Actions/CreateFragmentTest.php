<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Illuminate\Validation\ValidationException;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class CreateFragmentTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
    }

    public function test_it_can_create_fragment_model()
    {
        $fragmentId = app(CreateFragment::class)->handle(
            SnippetStub::resourceKey(),
            [],
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
            [],
            [
                'title' => 'bar',
                'title_trans' => [
                    'nl' => 'nl titel',
                    'en' => 'en title',
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

    public function test_it_only_validates_fragment_scoped_locales()
    {
        SnippetStub::setFieldsDefinition(function () {
            return [
                Text::make('title_trans')->locales()->required(),
            ];
        });

        $catched = false;

        try {
            $fragmentId = app(CreateFragment::class)->handle(
                SnippetStub::resourceKey(),
                ['nl'],
                [
                    'title' => 'bar',
                    'title_trans' => [
                        'nl' => '',
                    ],
                ],
                []
            );
        } catch (ValidationException $e) {

            $catched = true;

            $this->assertEquals([
                'title_trans.nl' => ['The title_trans field is required.'],
            ], $e->errors());
        }

        $this->assertTrue($catched);

    }

    public function test_it_saves_scoped_locales_but_still_saves_other_locales()
    {
        SnippetStub::setFieldsDefinition(function () {
            return [
                Text::make('title_trans')->locales()->required(),
            ];
        });

        $fragmentId = app(CreateFragment::class)->handle(
            SnippetStub::resourceKey(),
            ['nl'],
            [
                'title' => 'bar',
                'title_trans' => [
                    'nl' => 'title nl',
                    'en' => 'title en',
                ],
            ],
            []
        );

        $model = FragmentModel::find($fragmentId);

        $this->assertEquals('title nl', $model->dynamic('title_trans', 'nl'));
        $this->assertEquals('title en', $model->dynamic('title_trans', 'en'));
    }
}
