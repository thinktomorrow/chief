<?php

namespace Thinktomorrow\Chief\Models\Tests\UI;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Models\UI\Livewire\EditModel;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

class EditModelTest extends ChiefTestCase
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
                    Text::make('title')->locales()->required(),
                ]),
            ];
        });
    }

    public function test_it_can_open_the_edit_model_dialog()
    {
        $model = ArticlePage::create(['title' => 'My title']);

        $reference = $model->modelReference()->get();

        Livewire::test(EditModel::class, ['parentComponentId' => 'foo'])
            ->dispatch('open-edit-model', ['modelReference' => $reference])
            ->assertSet('isOpen', true)
            ->assertSet('modelReference', $model->modelReference());
    }

    public function test_it_can_close_the_dialog_and_reset_state()
    {
        $model = ArticlePage::create(['title' => 'My title']);

        $reference = $model->modelReference()->get();

        Livewire::test(EditModel::class, ['parentComponentId' => 'foo'])
            ->dispatch('open-edit-model', ['modelReference' => $reference])
            ->set('form', ['title' => 'Foo'])
            ->set('locales', ['nl'])
            ->set('scopedLocale', 'nl')
            ->call('close')
            ->assertSet('form', [])
            ->assertSet('locales', [])
            ->assertSet('scopedLocale', null)
            ->assertSet('isOpen', false);
    }

    public function test_it_validates_input()
    {
        $model = ArticlePage::create(['title' => ['nl' => 'Initial']]);

        $reference = $model->modelReference()->get();

        Livewire::test(EditModel::class, ['parentComponentId' => 'foo'])
            ->dispatch('open-edit-model', ['modelReference' => $reference])
            ->set('locales', ['nl'])
            ->set('form', ['title' => ['nl' => '']])
            ->call('save')
            ->assertHasErrors(['title.nl']);

        $this->assertDatabaseHas('article_pages', [
            'id' => $model->id,
            'values' => json_encode(['title' => ['nl' => 'Initial']]),
        ]);
    }

    public function test_it_saves_changes_to_the_model()
    {
        $model = ArticlePage::create(['title' => ['nl' => 'Initial title']]);

        $reference = $model->modelReference()->get();

        Livewire::test(EditModel::class, ['parentComponentId' => 'foo'])
            ->dispatch('open-edit-model', ['modelReference' => $reference])
            ->set('locales', ['nl'])
            ->set('form', ['title' => ['nl' => 'Updated title']])
            ->call('save');

        $this->assertDatabaseHas('article_pages', [
            'id' => $model->id,
            'values' => json_encode(['title' => ['nl' => 'Updated title']]),
        ]);
    }
}
