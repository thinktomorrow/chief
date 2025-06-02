<?php

namespace Thinktomorrow\Chief\Models\Tests\UI;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Models\UI\Livewire\CreateModelComponent;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

class CreateModelComponentTest extends ChiefTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class);

        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Form::make('main')->items([
                    Text::make('title_trans')->locales()->required(),
                ]),
            ];
        });
    }

    public function test_it_can_open_the_create_model_dialog()
    {
        Livewire::test(CreateModelComponent::class)
            ->dispatch('open-create-model', ['modelClass' => ArticlePage::class])
            ->assertSet('isOpen', true)
            ->assertSet('modelClass', ArticlePage::class);
    }

    public function test_it_cannot_scope_locale_if_locale_is_not_present()
    {
        Livewire::test(CreateModelComponent::class)
            ->set('scopedLocale', 'fr')
            ->set('locales', ['nl', 'en'])
            ->assertSet('scopedLocale', 'nl');
    }

    public function test_it_can_close_the_dialog_and_reset_state()
    {
        Livewire::test(CreateModelComponent::class)
            ->set('form', ['title' => 'Foo'])
            ->set('modelClass', ArticlePage::class)
            ->set('locales', ['nl'])
            ->set('scopedLocale', 'nl')
            ->set('isOpen', true)
            ->call('close')
            ->assertSet('form', [])
            ->assertSet('modelClass', '')
            ->assertSet('locales', [])
            ->assertSet('scopedLocale', null)
            ->assertSet('isOpen', false);
    }

    public function test_it_validates_input()
    {
        Livewire::test(CreateModelComponent::class)
            ->dispatch('open-create-model', ['modelClass' => ArticlePage::class])
            ->set('locales', ['nl'])
            ->set('form', ['title_trans' => ''])
            ->call('save')
            ->assertHasErrors(['title_trans.nl']);

        $this->assertDatabaseCount('article_pages', 0);
    }

    public function test_it_saves_locales()
    {
        Livewire::test(CreateModelComponent::class)
            ->dispatch('open-create-model', ['modelClass' => ArticlePage::class])
            ->set('locales', ['nl'])
            ->set('form', ['title_trans' => ['nl' => 'Test title']])
            ->call('save');

        $this->assertDatabaseHas('article_pages', [
            'id' => 1,
            'allowed_sites' => json_encode(['nl']),
        ]);
    }

    public function test_it_saves_and_redirects_to_edit_page()
    {
        Livewire::test(CreateModelComponent::class)
            ->dispatch('open-create-model', ['modelClass' => ArticlePage::class])
            ->set('locales', ['nl'])
            ->set('form', ['title_trans' => ['nl' => 'Test title']])
            ->call('save')
            ->assertRedirect('/admin/article_page/1/edit');

        $this->assertDatabaseCount('article_pages', 1);
    }
}
