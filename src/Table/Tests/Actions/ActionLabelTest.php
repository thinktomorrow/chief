<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Tests\Actions;

use Thinktomorrow\Chief\Table\Actions\Action;
use Thinktomorrow\Chief\Table\Tests\TestCase;

final class ActionLabelTest extends TestCase
{
    public function test_explicit_label_remains_visible_regardless_of_icon_order(): void
    {
        $labelFirst = Action::make('edit')->label('Edit participant')->iconEdit();
        $iconFirst = Action::make('edit')->iconEdit()->label('Edit participant');

        $this->assertSame('Edit participant', $labelFirst->getLabel());
        $this->assertSame('Edit participant', $iconFirst->getLabel());
    }

    public function test_converted_actions_preserve_whether_a_label_was_explicitly_set(): void
    {
        $defaultLabel = Action::make('edit');
        $explicitLabel = Action::make('edit')->label('Edit participant');

        foreach ([$defaultLabel->toRowAction(), $defaultLabel->toBulkAction()] as $action) {
            $this->assertSame('', $action->iconEdit()->getLabel());
        }

        foreach ([$explicitLabel->toRowAction(), $explicitLabel->toBulkAction()] as $action) {
            $this->assertSame('Edit participant', $action->iconEdit()->getLabel());
        }
    }
}
