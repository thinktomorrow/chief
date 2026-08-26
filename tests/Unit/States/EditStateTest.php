<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\States;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Thinktomorrow\Chief\Forms\Fields\Boolean;
use Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\EditState;
use Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\TransitionDto;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticleWithStateAdminConfig;
use Thinktomorrow\Chief\Tests\TestCase;

final class EditStateTest extends TestCase
{
    public function test_it_injects_confirmation_field_defaults_into_form_state(): void
    {
        $component = new EditStateWithConfirmationFields;
        $component->setFormData(['stale_value' => true]);

        $component->transition('default-on');

        $this->assertSame(['delete_registrations' => true], $component->getFormData());

        $component->closeConfirm();
        $component->transition('default-off');

        $this->assertSame(['delete_registrations' => false], $component->getFormData());
    }

    public function test_it_redirects_to_the_resource_index_when_the_model_no_longer_exists(): void
    {
        ArticleWithStateAdminConfig::migrateUp();
        chiefRegister()->resource(ArticleWithStateAdminConfig::class, PageManager::class);

        $article = ArticleWithStateAdminConfig::create(['article_state' => 'offline']);

        $component = Livewire::test(EditStateWithConfirmationFields::class, [
            'parentComponentId' => 'parent-component',
            'stateKey' => 'article_state',
            'model' => $article,
        ]);

        DB::table($article->getTable())->where('id', $article->getKey())->delete();

        $component
            ->call('saveState', 'default-on')
            ->assertRedirect($this->manager($article)->route('index'));
    }
}

final class EditStateWithConfirmationFields extends EditState
{
    /** @return Collection<int, TransitionDto> */
    public function getTransitions(): Collection
    {
        return collect([
            $this->transitionWithField('default-on', Boolean::make('delete_registrations')->defaultOn()),
            $this->transitionWithField('default-off', Boolean::make('delete_registrations')->defaultOff()),
        ]);
    }

    private function transitionWithField(string $key, Boolean $field): TransitionDto
    {
        return new TransitionDto(
            key: $key,
            label: $key,
            variant: 'grey',
            title: null,
            content: null,
            hasConfirmation: true,
            confirmationLabel: null,
            confirmationTitle: null,
            confirmationContent: null,
            confirmationFields: [$field],
            redirectTo: null,
            redirectNotification: null,
        );
    }
}
