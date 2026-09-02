<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\States;

use Livewire\Livewire;
use Thinktomorrow\Chief\ManagedModels\States\Actions\UpdateState;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\EditModelState;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

final class PageStateConfigTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class);
    }

    public function test_admin_cannot_publish_a_page_without_a_link(): void
    {
        $page = ArticlePage::create(['current_state' => PageState::draft->getValueAsString()]);

        Livewire::test(EditModelState::class, [
            'parentComponentId' => 'parent-component',
            'stateKey' => PageState::KEY,
            'model' => $page,
        ])
            ->call('open')
            ->call('saveState', 'publish')
            ->assertSet('errorMessage', 'Publiceren kan pas zodra er minstens één link is toegevoegd.')
            ->assertSee('Publiceren kan pas zodra er minstens één link is toegevoegd.');

        $this->assertSame(PageState::draft, $page->fresh()->getState(PageState::KEY));
    }

    public function test_admin_can_publish_a_page_with_a_link(): void
    {
        $page = ArticlePage::create();
        $this->createUrl($page);

        app(UpdateState::class)->handle(
            ArticlePageResource::resourceKey(),
            $page->modelReference(),
            PageState::KEY,
            'publish'
        );

        $this->assertSame(PageState::published, $page->fresh()->getState(PageState::KEY));
    }

    public function test_admin_can_open_the_link_drawer_from_the_publish_transition(): void
    {
        $page = ArticlePage::create(['current_state' => PageState::draft->getValueAsString()]);

        Livewire::test(EditModelState::class, [
            'parentComponentId' => 'parent-component',
            'stateKey' => PageState::KEY,
            'model' => $page,
        ])
            ->call('open')
            ->assertSee('Voeg eerst een link toe')
            ->assertSeeHtml('wire:click="editLinks"')
            ->call('editLinks')
            ->assertSet('isOpen', false)
            ->assertDispatched('open-edit-model-links');
    }

    public function test_add_link_action_is_hidden_when_the_page_has_a_link(): void
    {
        $page = ArticlePage::create(['current_state' => PageState::draft->getValueAsString()]);
        $this->createUrl($page);
        $page->unsetRelation('urls');

        Livewire::test(EditModelState::class, [
            'parentComponentId' => 'parent-component',
            'stateKey' => PageState::KEY,
            'model' => $page,
        ])
            ->call('open')
            ->assertDontSee('Voeg eerst een link toe')
            ->assertDontSeeHtml('wire:click="editLinks"');
    }

    public function test_published_page_without_a_link_is_shown_as_ready_for_publication(): void
    {
        $page = ArticlePage::create(['current_state' => PageState::published->getValueAsString()]);

        $this->assertSame(
            'Klaar voor publicatie (link ontbreekt nog)',
            $page->getStateConfig(PageState::KEY)->getStateLabel($page)
        );
    }

    public function test_published_page_without_a_link_can_be_unpublished(): void
    {
        $page = ArticlePage::create(['current_state' => PageState::published->getValueAsString()]);

        app(UpdateState::class)->handle(
            ArticlePageResource::resourceKey(),
            $page->modelReference(),
            PageState::KEY,
            'unpublish'
        );

        $this->assertSame(PageState::draft, $page->fresh()->getState(PageState::KEY));
    }

    private function createUrl(ArticlePage $page): UrlRecord
    {
        return UrlRecord::create([
            'site' => 'nl',
            'status' => LinkStatus::offline->value,
            'slug' => 'article',
            'model_type' => $page->getMorphClass(),
            'model_id' => $page->getKey(),
        ]);
    }
}
