<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Illuminate\Validation\ValidationException;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Fragments\App\Actions\UpdateFragment;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class UpdateFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_it_can_update_fragment()
    {
        SnippetStub::setFieldsDefinition(function () {
            return [
                Text::make('title')->required(),
            ];
        });

        [$context, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->owner, SnippetStub::class, null, 0, [
            'title' => 'baz',
        ]);

        app(UpdateFragment::class)->handle(
            $context->id,
            $fragment->getFragmentId(),
            ['nl', 'en'],
            ['title' => 'bar'],
            []
        );

        $model = FragmentModel::find($fragment->getFragmentId());

        $this->assertEquals('bar', $model->title);
    }

    public function test_it_can_update_fragment_with_localized_fields()
    {
        SnippetStub::setFieldsDefinition(function () {
            return [
                Text::make('title_trans')->locales()->required(),
            ];
        });

        [$context, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->owner, SnippetStub::class, null, 0, ['title_trans' => [
            'nl' => 'oude titel nl',
            'fr' => 'vieux titre fr',
            'en' => 'old title en',
        ]]);

        app(UpdateFragment::class)->handle(
            $context->id,
            $fragment->getFragmentId(),
            ['nl', 'en'],
            ['title_trans' => [
                'nl' => 'nl titel',
                'en' => 'en title',
            ]],
            []
        );

        $model = FragmentModel::find($fragment->getFragmentId());

        $this->assertEquals('nl titel', $model->dynamic('title_trans', 'nl'));
        $this->assertEquals('en title', $model->dynamic('title_trans', 'en'));
    }

    public function test_it_only_validates_fragment_scoped_locales()
    {
        SnippetStub::setFieldsDefinition(function () {
            return [
                Text::make('title_trans')->locales()->required(),
            ];
        });

        [$context, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->owner, SnippetStub::class, null, 0, ['title_trans' => [
            'nl' => 'oude titel nl',
            'fr' => 'vieux titre fr',
            'en' => 'old title en',
        ]]);

        $catched = false;

        try {
            app(UpdateFragment::class)->handle(
                $context->id,
                $fragment->getFragmentId(),
                ['nl', 'en'],
                ['title_trans' => [
                    'nl' => '',
                    'en' => '',
                ]],
                []
            );
        } catch (ValidationException $e) {

            $catched = true;

            $this->assertEquals([
                'title_trans.nl' => ['The nl title_trans field is required.'],
                'title_trans.en' => ['The en title_trans field is required.'],
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

        [$context, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->owner, SnippetStub::class, null, 0, ['title_trans' => [
            'nl' => 'oude titel nl',
            'fr' => 'vieux titre fr',
            'en' => 'old title en',
        ]]);

        app(UpdateFragment::class)->handle(
            $context->id,
            $fragment->getFragmentId(),
            ['nl', 'en'],
            ['title_trans' => [
                'nl' => 'title nl',
                'fr' => 'title fr',
                'en' => 'title en',
            ]],
            []
        );

        $model = FragmentModel::find($fragment->getFragmentId());

        $this->assertEquals('title nl', $model->dynamic('title_trans', 'nl'));
        $this->assertEquals('title fr', $model->dynamic('title_trans', 'fr'));
        $this->assertEquals('title en', $model->dynamic('title_trans', 'en'));
    }

    public function test_it_keeps_existing_locale_values_intact()
    {
        SnippetStub::setFieldsDefinition(function () {
            return [
                Text::make('title_trans')->locales()->required(),
            ];
        });

        [$context, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->owner, SnippetStub::class, null, 0, ['title_trans' => [
            'nl' => 'oude titel nl',
            'fr' => 'vieux titre fr',
            'en' => 'old title en',
        ]]);

        app(UpdateFragment::class)->handle(
            $context->id,
            $fragment->getFragmentId(),
            ['nl', 'en'],
            ['title_trans' => [
                'nl' => 'title nl',
                'en' => 'title en',
            ]],
            []
        );

        $model = FragmentModel::find($fragment->getFragmentId());

        $this->assertEquals('title nl', $model->dynamic('title_trans', 'nl'));
        $this->assertEquals('title en', $model->dynamic('title_trans', 'en'));
        $this->assertEquals('vieux titre fr', $model->dynamic('title_trans', 'fr'));
    }
}
