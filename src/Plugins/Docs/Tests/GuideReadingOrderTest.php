<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use Thinktomorrow\Chief\Plugins\Docs\DocSources;
use Thinktomorrow\Chief\Plugins\Docs\GuideNode;
use Thinktomorrow\Chief\Plugins\Docs\GuideTree;
use Thinktomorrow\Chief\Plugins\Docs\GuideTreeBuilder;

class GuideReadingOrderTest extends TestCase
{
    public function test_guides_are_read_in_sidebar_order_depth_first_without_page_less_sections(): void
    {
        $this->assertSame([
            'welkom',
            'woordenlijst',
            'opleidingen',
            'opleidingen/kalender',
            'opleidingen/inschrijvingen',
            'opleidingen/annuleren',
            'opleidingen/beheer/rollen',
            'instellingen/talen',
        ], $this->ids($this->tree(['admin'])->readingOrder()));
    }

    public function test_hidden_and_not_permitted_guides_are_not_read(): void
    {
        $tree = (new GuideTreeBuilder)->build(new DocSources($this->fixture('sidebar')))
            ->visibleTo([], ['opleidingen/inschrijvingen']);

        $this->assertSame([
            'welkom',
            'woordenlijst',
            'opleidingen',
            'opleidingen/kalender',
            'opleidingen/beheer/rollen',
        ], $this->ids($tree->readingOrder()));
    }

    public function test_previous_and_next_are_the_neighbours_in_reading_order(): void
    {
        $tree = $this->tree(['admin']);

        $this->assertSame('opleidingen/annuleren', $tree->previous($tree->find('opleidingen/beheer/rollen'))->id);
        $this->assertSame('instellingen/talen', $tree->next($tree->find('opleidingen/beheer/rollen'))->id);
        $this->assertSame('opleidingen', $tree->next($tree->find('woordenlijst'))->id);
    }

    public function test_the_first_guide_has_no_previous_and_the_last_has_no_next(): void
    {
        $tree = $this->tree(['admin']);

        $this->assertNull($tree->previous($tree->find('welkom')));
        $this->assertNull($tree->next($tree->find('instellingen/talen')));
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
