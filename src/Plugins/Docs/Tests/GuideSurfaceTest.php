<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use Thinktomorrow\Chief\Plugins\Docs\AdminSurface;
use Thinktomorrow\Chief\Plugins\Docs\DocSources;
use Thinktomorrow\Chief\Plugins\Docs\GuideNode;
use Thinktomorrow\Chief\Plugins\Docs\GuideSurface;
use Thinktomorrow\Chief\Plugins\Docs\GuideTree;
use Thinktomorrow\Chief\Plugins\Docs\GuideTreeBuilder;
use Thinktomorrow\Chief\Plugins\Docs\Markdown;
use Thinktomorrow\Chief\Plugins\Docs\Search\GuideSearch;
use Thinktomorrow\Chief\Plugins\Docs\Search\GuideSearchResult;

class GuideSurfaceTest extends TestCase
{
    public function test_links_between_guides_point_at_the_surface(): void
    {
        $this->useProjectGuides($this->fixture('links'));

        $html = $this->render('opleidingen', new FakeGuideSurface);

        $this->assertStringContainsString('href="https://example.test/gidsen/opleidingen/inschrijvingen">Inschrijven</a>', $html);
        $this->assertStringContainsString('href="https://example.test/gidsen/opleidingen/inschrijvingen#annuleren">Inschrijven met anker</a>', $html);
        $this->assertStringContainsString('href="https://example.test/gidsen/chief::paginas/status#publiceren">Status met anker</a>', $html);
        $this->assertStringNotContainsString('/admin/docs', $html);
    }

    public function test_assets_point_at_the_surface_versioned_by_their_modification_time(): void
    {
        $this->useProjectGuides($this->fixture('media'));

        $html = $this->render('opleidingen', new FakeGuideSurface);

        $this->assertStringContainsString(
            '<img src="https://example.test/gidsen/opleidingen/schermen/aanmaken.png?v='.filemtime($this->fixture('media/opleidingen/schermen/aanmaken.png')).'"',
            $html,
        );
        $this->assertStringNotContainsString('/admin/docs', $html);
    }

    public function test_callouts_render_as_plain_markup_for_a_surface_other_than_the_admin(): void
    {
        $html = $this->renderMarkdown("> [!TIP]\n> Kies een interne pagina.\n\n> [!NOTE]\n> Een bestand is gedeeld.\n\n> [!WARNING]\n> Verwijderen is **definitief**.", new FakeGuideSurface);

        $this->assertStringContainsString("<aside class=\"guide-callout\" data-callout=\"tip\">\n<p class=\"guide-callout-title\">Tip</p>\n<p>Kies een interne pagina.</p>\n</aside>", $html);
        $this->assertStringContainsString("<aside class=\"guide-callout\" data-callout=\"note\">\n<p class=\"guide-callout-title\">Goed om te weten</p>\n<p>Een bestand is gedeeld.</p>\n</aside>", $html);
        $this->assertStringContainsString("<aside class=\"guide-callout\" data-callout=\"warning\">\n<p class=\"guide-callout-title\">Let op</p>\n<p>Verwijderen is <strong>definitief</strong>.</p>\n</aside>", $html);
        $this->assertStringNotContainsString('class="callout', $html);
    }

    public function test_a_surface_can_replace_a_renderer(): void
    {
        $html = $this->renderMarkdown("> [!TIP]\n> Kies een interne pagina.", new FakeGuideSurface(rendersOwnCallouts: true));

        $this->assertSame('<div class="eigen-callout"><p>Kies een interne pagina.</p></div>', trim($html));
    }

    public function test_the_admin_renders_callouts_with_chiefs_callout_component(): void
    {
        $html = $this->renderMarkdown("> [!TIP]\n> Kies een interne pagina.", new AdminSurface);

        $this->assertMatchesRegularExpression('#<div\s+class="callout [^"]*callout-green"\s*>.*?<p data-slot="title">\s*Tip\s*</p>.*?Kies een interne pagina\.#s', $html);
        $this->assertStringNotContainsString('guide-callout', $html);
    }

    public function test_search_results_point_at_the_surface(): void
    {
        $this->useOnlyProjectGuides($this->fixture('search'));

        $results = $this->app->make(GuideSearch::class)->search($this->publicTree(), 'archiveren', new FakeGuideSurface);

        $this->assertSame(['https://example.test/gidsen/paginas/status#archiveren'], array_map(fn (GuideSearchResult $result) => $result->url, $results));
    }

    private function renderMarkdown(string $markdown, GuideSurface $surface): string
    {
        $guide = new GuideNode('gids', 'Gids', markdown: $markdown);
        $tree = new GuideTree(['gids' => $guide], ['gids' => null], ['' => ['gids']]);

        return $this->app->make(Markdown::class)->renderGuide($guide, $tree, $surface)->content;
    }

    private function render(string $guideId, FakeGuideSurface $surface): string
    {
        $tree = $this->publicTree();

        return $this->app->make(Markdown::class)->renderGuide($tree->find($guideId), $tree, $surface)->content;
    }

    private function publicTree(): GuideTree
    {
        return $this->app->make(GuideTreeBuilder::class)
            ->build($this->app->make(DocSources::class))
            ->visibleTo([], []);
    }
}
