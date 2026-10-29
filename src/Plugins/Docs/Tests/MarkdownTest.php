<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use Thinktomorrow\Chief\Plugins\Docs\Markdown;

class MarkdownTest extends TestCase
{
    protected function tearDown(): void
    {
        Markdown::flushConfigureCallbacks();

        parent::tearDown();
    }

    public function test_it_renders_tables_and_keeps_unsafe_html_and_links_out(): void
    {
        $html = $this->convert("| Onderdeel | Status |\n| --- | --- |\n| Inschrijving | **Open** |\n\n<script>alert('x')</script>\n\n[Onveilig](javascript:alert(1))");

        $this->assertStringContainsString('<table>', $html);
        $this->assertStringContainsString('<strong>Open</strong>', $html);
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringNotContainsString('href="javascript:', $html);
    }

    public function test_alerts_render_as_callouts_with_dutch_labels(): void
    {
        $html = $this->convert("> [!TIP]\n> Kies een interne pagina.\n\n> [!NOTE]\n> Een bestand is gedeeld.\n\n> [!WARNING]\n> Verwijderen is **definitief**.");

        $this->assertStringNotContainsString('<blockquote>', $html);
        $this->assertStringNotContainsString('[!', $html);
        $this->assertMatchesRegularExpression('#<div\s+class="callout [^"]*callout-green"\s*>.*?<p data-slot="title">\s*Tip\s*</p>.*?Kies een interne pagina\.#s', $html);
        $this->assertMatchesRegularExpression('#<div\s+class="callout [^"]*callout-blue"\s*>.*?<p data-slot="title">\s*Goed om te weten\s*</p>.*?Een bestand is gedeeld\.#s', $html);
        $this->assertMatchesRegularExpression('#<div\s+class="callout [^"]*callout-orange"\s*>.*?<p data-slot="title">\s*Let op\s*</p>.*?Verwijderen is <strong>definitief</strong>\.#s', $html);
    }

    public function test_a_plain_tip_stays_a_blockquote(): void
    {
        $html = $this->convert("> Tip: kies een interne pagina.\n\n> [!ONBEKEND]\n> Geen alert.");

        $this->assertSame(2, substr_count($html, '<blockquote>'));
        $this->assertStringNotContainsString('callout', $html);
    }

    public function test_h2_and_h3_headings_get_dutch_slugged_ids_and_a_permalink_badge(): void
    {
        $html = $this->convert("## Hoe publiceer ik?\n\n## Mail @redactie\n\n### Wat is één fragment?\n\n#### Diepere kop");

        $this->assertMatchesRegularExpression('#<h2[^>]* id="hoe-publiceer-ik"[^>]*>.*?<a [^>]*href="\#hoe-publiceer-ik"[^>]*>\#</a>#s', $html);
        $this->assertMatchesRegularExpression('#<h3[^>]* id="wat-is-een-fragment"[^>]*>.*?<a [^>]*href="\#wat-is-een-fragment"[^>]*>\#</a>#s', $html);
        $this->assertStringContainsString('id="mail-redactie"', $html);
        $this->assertStringContainsString('<h4>Diepere kop</h4>', $html);
    }

    public function test_external_links_open_in_a_new_window_and_admin_links_do_not(): void
    {
        $html = $this->convert('[Extern](https://example.com/hulp) [Admin](http://localhost/admin/pages) [Hoofdletters](http://LOCALHOST/admin) [Relatief](/admin/docs/chief::media)');

        $this->assertStringContainsString('<a rel="noopener" target="_blank" href="https://example.com/hulp">Extern</a>', $html);
        $this->assertStringContainsString('<a href="http://localhost/admin/pages">Admin</a>', $html);
        $this->assertStringContainsString('<a href="http://LOCALHOST/admin">Hoofdletters</a>', $html);
        $this->assertStringContainsString('<a href="/admin/docs/chief::media">Relatief</a>', $html);
    }

    public function test_the_first_h1_is_removed_because_the_page_shows_the_title(): void
    {
        $html = $this->convert("Inleiding.\n\n# Titel van de gids\n\nTekst.\n\n# Tweede titel");

        $this->assertStringNotContainsString('Titel van de gids', $html);
        $this->assertStringContainsString('<p>Inleiding.</p>', $html);
        $this->assertStringContainsString('<h1>Tweede titel</h1>', $html);
    }

    public function test_only_a_top_level_h1_is_removed(): void
    {
        $html = $this->convert("> # Geciteerde kop\n\n# Titel van de gids\n\nTekst.");

        $this->assertStringContainsString('<h1>Geciteerde kop</h1>', $html);
        $this->assertStringNotContainsString('Titel van de gids', $html);
    }

    public function test_a_configure_callback_is_applied_to_the_environment(): void
    {
        Markdown::configureUsing(fn (Environment $environment) => $environment->addExtension(new SmartPunctExtension));

        $this->assertStringContainsString('<p>Even wachten…</p>', $this->convert('Even wachten...'));
    }

    private function convert(string $markdown): string
    {
        return $this->app->make(Markdown::class)->convert($markdown);
    }
}
