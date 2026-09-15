<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Tests\Actions;

use Illuminate\Support\Facades\Blade;
use Thinktomorrow\Chief\Table\Actions\Action;
use Thinktomorrow\Chief\Table\Tests\TestCase;

final class ActionLinkTest extends TestCase
{
    public function test_action_conversion_preserves_the_link_target(): void
    {
        foreach ([true, false] as $newTab) {
            $action = Action::make('view')->link('/certificate')->openInNewTab($newTab);

            $this->assertSame($newTab, $action->toRowAction()->shouldOpenInNewTab());
            $this->assertSame($newTab, $action->toBulkAction()->shouldOpenInNewTab());
        }
    }

    public function test_link_components_render_the_target_without_livewire_click_handlers(): void
    {
        foreach (['button', 'dropdown.item'] as $component) {
            foreach ([true, false] as $newTab) {
                $action = Action::make('view')->label('View certificate')->link('/certificate')->openInNewTab($newTab)->toRowAction();
                $html = Blade::render('<x-chief-table::action.'.$component.' :action="$action" wire:click="applyRowAction" />', ['action' => $action]);

                $this->assertStringContainsString('href="/certificate"', $html);
                $this->assertStringNotContainsString('wire:click', $html);
                if ($newTab) {
                    $this->assertStringContainsString('target="_blank"', $html);
                    $this->assertStringContainsString('rel="noopener"', $html);
                } else {
                    $this->assertStringNotContainsString('target="_blank"', $html);
                }
            }
        }
    }

    public function test_non_link_actions_keep_their_livewire_click_handler(): void
    {
        foreach (['button', 'dropdown.item'] as $component) {
            $html = Blade::render('<x-chief-table::action.'.$component.' :action="$action" wire:click="applyRowAction" />', [
                'action' => Action::make('edit')->openInNewTab(),
            ]);

            $this->assertStringContainsString('<button', $html);
            $this->assertStringContainsString('wire:click="applyRowAction"', $html);
            $this->assertStringNotContainsString('target="_blank"', $html);
        }
    }
}
