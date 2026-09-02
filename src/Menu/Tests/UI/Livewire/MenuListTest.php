<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\Tests\UI\Livewire;

use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Menu\UI\Livewire\MenuList;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class MenuListTest extends ChiefTestCase
{
    public function test_it_composes_menu_items_once_per_component_request(): void
    {
        $menus = collect();
        $query = $this->createMock(ComposeLivewireDto::class);
        $query->expects($this->once())
            ->method('getMenus')
            ->with('main')
            ->willReturn($menus);

        app()->instance(ComposeLivewireDto::class, $query);

        $component = new MenuList;
        $component->type = 'main';

        $this->assertSame($menus, $component->getItems());
        $this->assertSame($menus, $component->getItems());
    }
}
