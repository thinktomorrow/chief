<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tests\UI\Livewire;

use Livewire\Livewire;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Forms\UI\Livewire\FormComponent;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class FormComponentTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class);

        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Form::make('form-id')->components([
                    Text::make('title'),
                    Text::make('content_trans')->locales(),
                ]),
            ];
        });
    }

    public function test_it_renders_the_correct_form_view()
    {
        $model = ArticlePage::create(['title' => 'foo', 'content_trans' => [
            'nl' => 'bar',
            'en' => 'baz',
        ]]);
        $form = Form::make('form-id');

        Livewire::test(FormComponent::class, [
            'model' => $model,
            'form' => $form,
        ])
            ->assertViewIs('chief-form::livewire.form')
            ->assertSeeHtml(['foo', 'bar']); // For nl
    }

    public function test_it_renders_the_correct_form_view_per_locale()
    {
        $model = ArticlePage::create(['title' => 'foo', 'content_trans' => [
            'nl' => 'bar',
            'en' => 'baz',
        ]]);
        $form = Form::make('form-id');

        Livewire::test(FormComponent::class, [
            'model' => $model,
            'form' => $form,
        ])
            ->call('onScopedToLocale', 'en')
            ->assertViewIs('chief-form::livewire.form')
            ->assertSeeHtml(['foo', 'baz']); // For en
    }

    public function test_it_can_edit_form_and_emits_event()
    {
        $model = ArticlePage::create();
        $form = Form::make('form-id');

        $component = Livewire::test(FormComponent::class, [
            'model' => $model,
            'form' => $form,
        ]);

        $component
            ->call('editForm')
            ->assertDispatched('open-'.$component->instance()->getId());
    }

    public function test_it_can_update_locale_scope()
    {
        $model = ArticlePage::create();
        $form = Form::make('form-id');

        Livewire::test(FormComponent::class, [
            'model' => $model,
            'form' => $form,
        ])
            ->call('onScopedToLocale', 'fr')
            ->assertSet('scopedLocale', 'fr');
    }
}
