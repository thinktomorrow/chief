<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\States;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Thinktomorrow\Chief\Forms\Fields\Boolean;
use Thinktomorrow\Chief\ManagedModels\States\State\StateConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StateException;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\ManagedModels\States\State\StateTransitionGuard;
use Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\EditState;
use Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\TransitionDto;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticleStateAdminConfig;
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

    public function test_it_shows_the_guard_message_without_persisting_the_transition(): void
    {
        GuardedArticleWithStateAdminConfig::migrateUp();
        chiefRegister()->resource(GuardedArticleWithStateAdminConfig::class, PageManager::class);

        $article = GuardedArticleWithStateAdminConfig::create(['article_state' => 'offline']);

        Livewire::test(EditState::class, [
            'parentComponentId' => 'parent-component',
            'stateKey' => 'article_state',
            'model' => $article,
        ])
            ->call('saveState', 'publish')
            ->assertSet('errorMessage', 'Publishing is not allowed.');

        $this->assertSame('offline', $article->fresh()->article_state);
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

final class GuardedArticleWithStateAdminConfig extends ArticleWithStateAdminConfig
{
    public function getStateConfig(string $stateKey): StateConfig
    {
        return new GuardedArticleStateAdminConfig;
    }
}

final class GuardedArticleStateAdminConfig extends ArticleStateAdminConfig implements StateTransitionGuard
{
    public function getTransitionType(StatefulContract $statefulContract, string $transitionKey): ?string
    {
        return 'grey';
    }

    public function assertCanTransition(StatefulContract $statefulContract, string $transition, array $data): void
    {
        throw new StateException('Publishing is not allowed.');
    }
}
