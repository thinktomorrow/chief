<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use Thinktomorrow\Chief\Plugins\Docs\DocSources;
use Thinktomorrow\Chief\Plugins\Docs\GuideGroup;
use Thinktomorrow\Chief\Plugins\Docs\GuideNode;
use Thinktomorrow\Chief\Plugins\Docs\GuideTree;
use Thinktomorrow\Chief\Plugins\Docs\GuideTreeBuilder;

class GuideGroupsTest extends TestCase
{
    public function test_top_level_guides_without_children_are_grouped_first_under_aan_de_slag(): void
    {
        $groups = $this->tree(['admin'])->groups();

        $this->assertSame('Aan de slag', $groups[0]->title);
        $this->assertNull($groups[0]->section);
        $this->assertSame(['welkom', 'woordenlijst'], $this->ids($groups[0]->nodes));
    }

    public function test_each_top_level_section_is_a_group_that_lists_its_own_page_first(): void
    {
        $groups = $this->tree(['admin'])->groups();

        $this->assertSame(['Aan de slag', 'Opleidingen', 'Instellingen'], array_map(fn (GuideGroup $group) => $group->title, $groups));

        $this->assertSame('opleidingen', $groups[1]->section->id);
        $this->assertSame(['opleidingen', 'opleidingen/kalender', 'opleidingen/inschrijvingen', 'opleidingen/beheer'], $this->ids($groups[1]->nodes));

        $this->assertSame('instellingen', $groups[2]->section->id);
        $this->assertSame(['instellingen/talen'], $this->ids($groups[2]->nodes));
    }

    public function test_a_section_without_page_or_visible_guides_has_no_group(): void
    {
        $groups = $this->tree([])->groups();

        $this->assertSame(['Aan de slag', 'Opleidingen'], array_map(fn (GuideGroup $group) => $group->title, $groups));
    }

    public function test_there_is_no_aan_de_slag_group_without_top_level_guides_without_children(): void
    {
        $groups = (new GuideTreeBuilder)->build(new DocSources($this->fixture('sidebar')))
            ->visibleTo(['admin'], ['welkom', 'woordenlijst'])
            ->groups();

        $this->assertSame(['Opleidingen', 'Instellingen'], array_map(fn (GuideGroup $group) => $group->title, $groups));
    }

    /**
     * @param  list<string>  $roleNames
     */
    private function tree(array $roleNames): GuideTree
    {
        return (new GuideTreeBuilder)->build(new DocSources($this->fixture('sidebar')))->visibleTo($roleNames, []);
    }

    /**
     * @param  list<GuideNode>  $nodes
     * @return list<string>
     */
    private function ids(array $nodes): array
    {
        return array_map(fn (GuideNode $node) => $node->id, $nodes);
    }
}
