<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use Thinktomorrow\Chief\Plugins\Docs\DocSources;
use Thinktomorrow\Chief\Plugins\Docs\GuideNode;
use Thinktomorrow\Chief\Plugins\Docs\GuideTree;

class GuideTreeTest extends TestCase
{
    private GuideTree $tree;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useProjectGuides($this->fixture('project'));

        $this->tree = $this->guideTree();
    }

    public function test_title_falls_back_to_the_first_heading_then_the_filename(): void
    {
        $this->assertSame('Opleidingen', $this->tree->find('opleidingen')->title);
        $this->assertSame('Inschrijvingen beheren', $this->tree->find('opleidingen/inschrijvingen')->title);
        $this->assertSame('Losse Gids', $this->tree->find('losse_gids')->title);
    }

    public function test_front_matter_description_and_order_are_read(): void
    {
        $this->assertSame('Inschrijvingen opvolgen.', $this->tree->find('opleidingen/inschrijvingen')->description);
        $this->assertSame(2, $this->tree->find('opleidingen')->order);
    }

    public function test_a_folder_without_index_guide_is_a_section_labelled_by_its_name(): void
    {
        $section = $this->tree->find('instellingen-van-de-site');

        $this->assertFalse($section->hasPage());
        $this->assertSame('Instellingen Van De Site', $section->title);
        $this->assertSame(['Talen instellen'], $this->titles($this->tree->children($section)));
    }

    public function test_siblings_sort_by_order_then_title_with_unordered_guides_last(): void
    {
        $this->assertSame(
            ['Annuleren', 'Kalender', 'Aanvragen', 'Inschrijvingen beheren', 'Verplaatst'],
            $this->titles($this->tree->children($this->tree->find('opleidingen'))),
        );
    }

    public function test_parent_reattaches_a_guide(): void
    {
        $this->assertSame(['opleidingen'], $this->ids($this->tree->ancestors($this->tree->find('verplaatst'))));
    }

    public function test_a_missing_parent_falls_back_to_the_top_level(): void
    {
        $this->assertContains('weesje', $this->ids($this->tree->roots()));
    }

    public function test_a_parent_cycle_falls_back_to_the_top_level(): void
    {
        $roots = $this->ids($this->tree->roots());

        $this->assertContains('lus-a', $roots);
        $this->assertContains('lus-b', $roots);
    }

    public function test_project_and_chief_guides_merge_into_one_tree(): void
    {
        $roots = $this->ids($this->tree->roots());

        $this->assertContains('opleidingen', $roots);
        $this->assertContains('chief::welkom', $roots);
        $this->assertContains('chief::paginas/status', $this->ids($this->tree->children($this->tree->find('chief::paginas'))));
    }

    public function test_a_guide_can_attach_to_a_section_of_another_source(): void
    {
        app(DocSources::class)->add('acme', $this->fixture('acme'));

        $tree = $this->asAdmin()->get('/admin/docs')->viewData('tree');

        $this->assertSame(['chief::paginas'], $this->ids($tree->ancestors($tree->find('acme::archief'))));
    }

    /** @param list<GuideNode> $nodes */
    private function titles(array $nodes): array
    {
        return array_map(fn (GuideNode $node) => $node->title, $nodes);
    }

    /** @param list<GuideNode> $nodes */
    private function ids(array $nodes): array
    {
        return array_map(fn (GuideNode $node) => $node->id, $nodes);
    }
}
