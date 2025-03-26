<?php

namespace Thinktomorrow\Chief\Menu\Tests;

use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class UpdateMenuItemTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        app()->setLocale('nl');
    }

    public function test_a_menuitem_can_be_nested()
    {
        $parent = MenuItem::create();
        $child = MenuItem::create();

        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $child->id), $this->validParams([
                'allow_parent' => true,
                'parent_id' => $parent->id,
            ]))->assertStatus(302);

        $this->assertCount(1, $parent->fresh()->children);
        $this->assertEquals($parent->id, MenuItem::find(2)->parent->id); // Hardcoded assumption that newly created has id of 2

        // If item can be set to top level again
        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $child->id), $this->validParams([
                'allow_parent' => false,
            ]));

        $this->assertCount(0, $parent->fresh()->children);
        $this->assertNull(MenuItem::find(2)->parent_id); // Hardcoded assumption that newly created has id of 2
    }

    public function test_a_menuitem_can_be_sorted()
    {
        $secondItem = MenuItem::create(['type' => 'custom', 'order' => 2]);
        $firstItem = MenuItem::create(['type' => 'custom', 'order' => 1]);
        $thirdItem = MenuItem::create(['type' => 'custom', 'order' => 3]);

        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $secondItem->id), $this->validParams([
                'order' => 1,
            ]));

        $items = MenuItem::all();
        $this->assertCount(3, $items);
        $this->assertEquals($secondItem->id, $items[0]->id);
        $this->assertEquals($firstItem->id, $items[1]->id);
        $this->assertEquals($thirdItem->id, $items[2]->id);
    }

    public function test_url_field_is_sanitized_to_valid_url()
    {
        $menuitem = MenuItem::create(['type' => 'custom', 'url' => ['nl' => 'http://google.com']]);

        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams([
                'trans.nl.label' => 'new label',
                'trans.nl.url' => 'thinktomorrow.be',
            ]));

        $this->assertEquals('https://thinktomorrow.be', $menuitem->fresh()->url);
    }
}
